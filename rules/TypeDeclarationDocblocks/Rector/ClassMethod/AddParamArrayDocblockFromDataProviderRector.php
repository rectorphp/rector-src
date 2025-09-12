<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\PHPUnit\NodeAnalyzer\TestsNodeAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclarationDocblocks\NodeFinder\DataProviderMethodsFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDataProviderRector\AddParamArrayDocblockFromDataProviderRectorTest
 */
final class AddParamArrayDocblockFromDataProviderRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly TestsNodeAnalyzer $testsNodeAnalyzer,
        private readonly DataProviderMethodsFinder $dataProviderMethodsFinder
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add @param docblock array type, based on data provider data type',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class SomeTest extends TestCase
{
    #[DataProvider('provideData')]
    public function test(array $names): void
    {
    }

    public static function provideData()
    {
        yield [['Tom', 'John']];
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class SomeTest extends TestCase
{
    /**
     * @param string[] $names
     */
    #[DataProvider('provideData')]
    public function test(array $names): void
    {
    }

    public static function provideData()
    {
        yield [['Tom', 'John']];
    }
}
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->getParams() === []) {
            return null;
        }

        if (! $this->testsNodeAnalyzer->isTestClassMethod($node)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        // nothing relevant here
        $dataProviderNodes = $this->dataProviderMethodsFinder->findDataProviderNodes($node);
        if ($dataProviderNodes->isEmpty()) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getParams() as $param) {
            if (! $param->type instanceof Node) {
                continue;
            }

            if (! $this->isName($param->type, 'array')) {
                continue;
            }

            /** @var string $paramName */
            $paramName = $this->getName($param->var);

            $paramTagValueNode = $phpDocInfo->getParamTagValueByName($paramName);

            // already defined, lets skip it
            if ($paramTagValueNode instanceof ParamTagValueNode) {
                continue;
            }

            dump($dataProviderNodes);
            die;

            // @todo start here
            //            $paramTagValueNode = $this->createParamTagValueNode($paramName, 'string');
            //            $phpDocInfo->addTagValueNode($paramTagValueNode);
            //            $hasChanged = true;
            //            continue;
        }

        //        if ($hasChanged === false) {
        //            return null;
        //        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }
}
