<?php

declare(strict_types=1);

namespace Rector\DowngradePhp70\Rector\String_;

use Nette\Utils\Strings;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeTraverser;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\PhpParser\Parser\InlineCodeParser;
use Rector\Core\Rector\AbstractRector;
use Rector\DowngradePhp70\Rector\FunctionLike\DowngradeScalarTypeDeclarationRector;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeVoidTypeDeclarationRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @changelog https://github.com/symfony/symfony/blob/ad91659ea9b2a964f933bf27d0d1f1ef60fe9417/src/Symfony/Component/DependencyInjection/Dumper/PhpDumper.php#L1516
 *
 * @see \Rector\Tests\DowngradePhp70\Rector\String_\DowngradeGeneratedScalarTypesRector\DowngradeGeneratedScalarTypesRectorTest
 */
final class DowngradeGeneratedScalarTypesRector extends AbstractRector
{
    /**
     * Extends list here as needed
     * @var string[]
     */
    private const FILES_TO_INCLUDE = [
        // https://github.com/symfony/symfony/blob/ad91659ea9b2a964f933bf27d0d1f1ef60fe9417/src/Symfony/Component/DependencyInjection/Dumper/PhpDumper.php#L1516
        'vendor/symfony/dependency-injection/Dumper/PhpDumper.php',
    ];

    /**
     * @var PhpRectorInterface[]
     */
    private array $phpRectors = [];

    public function __construct(
        private InlineCodeParser $inlineCodeParser,
        DowngradeScalarTypeDeclarationRector $downgradeScalarTypeDeclarationRector,
        DowngradeVoidTypeDeclarationRector $downgradeVoidTypeDeclarationRector
    ) {
        $this->phpRectors = [$downgradeScalarTypeDeclarationRector, $downgradeVoidTypeDeclarationRector];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Refactor scalar types in PHP code in string snippets, e.g. generated container code from symfony/dependency-injection',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$code = <<<'EOF'
    public function getParameter(string $name)
    {
        return $name;
    }
EOF;
CODE_SAMPLE

                    ,
                    <<<'CODE_SAMPLE'
$code = <<<'EOF'
    /**
     * @param string $name
     */
    public function getParameter($name)
    {
        return $name;
    }
EOF;
CODE_SAMPLE
                ),

            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    /**
     * @param String_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $smartFileInfo = $this->file->getSmartFileInfo();

        // this rule is parsing strings, so it heavy on performance; to lower it, we'll process only known opt-in files
        if (! $this->isRelevantFileInfo($smartFileInfo)) {
            return null;
        }

        $stringKind = $node->getAttribute(AttributeKey::KIND);
        if (! in_array($stringKind, [String_::KIND_NOWDOC, String_::KIND_HEREDOC], true)) {
            return null;
        }

        // we assume its a function list - see https://github.com/symfony/symfony/blob/ad91659ea9b2a964f933bf27d0d1f1ef60fe9417/src/Symfony/Component/DependencyInjection/Dumper/PhpDumper.php#L1513-L1560

        try {
            $nodes = $this->inlineCodeParser->parse('<?php class SomeClass { ' . $node->value . ' }');
        } catch (Error) {
            // nothing we can do
            return null;
        }

        if ($nodes === []) {
            return null;
        }

        // * replace scalar types with docs
        // * remove return type
        // somehow we want to call all Rector rules here
        $nodeTraverser = $this->createNodeTraverser();
        $changedNodes = $nodeTraverser->traverse($nodes);
        if (! $changedNodes[0] instanceof Class_) {
            return null;
        }

        $node->value = $this->printClassStmts($changedNodes[0]);
        return $node;
    }

    private function isRelevantFileInfo(SmartFileInfo $fileInfo): bool
    {
        // for tests
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return true;
        }

        foreach (self::FILES_TO_INCLUDE as $fileToInclude) {
            if (\str_ends_with($fileInfo->getRealPath(), $fileToInclude)) {
                return true;
            }
        }

        return false;
    }

    private function printClassStmts(Class_ $class): string
    {
        $refactoredContent = '';
        foreach ($class->stmts as $classStmt) {
            $refactoredContent .= $this->betterStandardPrinter->prettyPrint([$classStmt]) . PHP_EOL;
        }

        return $refactoredContent;
    }

    private function createNodeTraverser(): NodeTraverser
    {
        $nodeTraverser = new NodeTraverser();
        foreach ($this->phpRectors as $phpRector) {
            $nodeTraverser->addVisitor($phpRector);
        }
        return $nodeTraverser;
    }
}
