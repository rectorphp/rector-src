<?php

declare(strict_types=1);

namespace Rector\Php86\Rector\FunctionLike;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\DeadCode\PhpDoc\DeadParamTagValueNodeAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

/**
 * @see https://wiki.php.net/rfc/parameter-doccomments
 * @see \Rector\Tests\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector\ParamTagDescriptionToParamDocCommentRectorTest
 */
final class ParamTagDescriptionToParamDocCommentRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DeadParamTagValueNodeAnalyzer $deadParamTagValueNodeAnalyzer,
        private readonly PhpDocTagRemover $phpDocTagRemover,
        private readonly DocBlockUpdater $docBlockUpdater,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move @param descriptions to doc comments on parameters that start their own line', [
            new CodeSample(
                <<<'CODE_SAMPLE'
/**
 * @param string $query Terms to search
 * @param int $limit Maximum number of results
 */
function search(
    string $query,
    int $limit = 10,
): array {
    return [];
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
function search(
    /** Terms to search */
    string $query,
    /** Maximum number of results */
    int $limit = 10,
): array {
    return [];
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $hasChanged = false;
        $previousLine = $node->name->getEndLine();

        foreach ($node->params as $param) {
            // a doc comment on a parameter that shares its line would be printed inline
            $startsOwnLine = $param->getStartLine() > $previousLine;
            $previousLine = $param->getEndLine();

            if (! $startsOwnLine || $param->getDocComment() instanceof Doc) {
                continue;
            }

            $paramTagValueNode = $phpDocInfo->getParamTagValueByName($this->getName($param));
            if (! $paramTagValueNode instanceof ParamTagValueNode || $paramTagValueNode->description === '') {
                continue;
            }

            $param->setDocComment(new Doc($this->createDocCommentText($paramTagValueNode->description)));
            $paramTagValueNode->description = '';
            if ($this->deadParamTagValueNodeAnalyzer->isDead($paramTagValueNode, $node)) {
                $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $paramTagValueNode);
            }

            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_86;
    }

    private function createDocCommentText(string $description): string
    {
        if (! str_contains($description, "\n")) {
            return '/** ' . $description . ' */';
        }

        $lines = array_map(trim(...), explode("\n", $description));

        return "/**\n * " . implode("\n * ", $lines) . "\n */";
    }
}
