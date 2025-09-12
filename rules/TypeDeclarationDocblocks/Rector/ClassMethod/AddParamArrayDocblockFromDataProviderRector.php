<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->testsNodeAnalyzer->isInTestClass($node)) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if ($classMethod->getParams() === []) {
                continue;
            }

            if (! $this->testsNodeAnalyzer->isTestClassMethod($classMethod)) {
                continue;
            }

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);

            $dataProviderNodes = $this->dataProviderMethodsFinder->findDataProviderNodes($node, $classMethod);
            if ($dataProviderNodes->getClassMethods() === []) {
                continue;
            }

            foreach ($classMethod->getParams() as $paramPosition => $param) {
                // we are intersted only in array params
                if (! $param->type instanceof Node || ! $this->isName($param->type, 'array')) {
                    continue;
                }

                /** @var string $paramName */
                $paramName = $this->getName($param->var);

                $paramTagValueNode = $phpDocInfo->getParamTagValueByName($paramName);

                // already defined, lets skip it
                if ($paramTagValueNode instanceof ParamTagValueNode) {
                    continue;
                }

                foreach ($dataProviderNodes->getClassMethods() as $dataProviderClassMethod) {
                    // try to resolve array type on position X
                    dump($paramPosition);
                    die;
                }

                // @todo start here
                //            $paramTagValueNode = $this->createParamTagValueNode($paramName, 'string');
                //            $phpDocInfo->addTagValueNode($paramTagValueNode);
                //            $hasChanged = true;
                //            continue;
            }

            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }
}
