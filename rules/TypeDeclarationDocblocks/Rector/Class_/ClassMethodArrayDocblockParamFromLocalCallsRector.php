<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\Class_;

use PHPStan\Type\Type;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\PhpParser\NodeFinder\LocalMethodCallFinder;
use Rector\Privatization\TypeManipulator\TypeNormalizer;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\NodeAnalyzer\CallTypesResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\ClassMethodArrayDocblockParamFromLocalCallsRectorTest
 */
final class ClassMethodArrayDocblockParamFromLocalCallsRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly CallTypesResolver $callTypesResolver,
        private readonly LocalMethodCallFinder $localMethodCallFinder,
        private readonly TypeNormalizer $typeNormalizer
    ) {
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add @param array docblock to a class method based on local call types', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function go()
    {
        $this->run(['item1', 'item2']);
    }

    private function run(array $items)
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function go()
    {
        $this->run(['item1', 'item2']);
    }

    /**
     * @param string[] $items
     */
    private function run(array $items)
    {
    }
}
CODE_SAMPLE
            ),

        ]);
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if ($classMethod->getParams() === []) {
                continue;
            }

            $classMethodPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);

            $methodCalls = $this->localMethodCallFinder->match($node, $classMethod);
            $classMethodParameterTypes = $this->callTypesResolver->resolveStrictTypesFromCalls($methodCalls);

            foreach ($classMethod->getParams() as $parameterPosition => $param) {
                if ($param->type === null) {
                    continue;
                }
                if (! $this->isName($param->type, 'array')) {
                    continue;
                }
                $parameterName = $this->getName($param);
                $parameterTagValueNode = $classMethodPhpDocInfo->getParamTagValueByName($parameterName);

                // already known, skip
                if ($parameterTagValueNode instanceof ParamTagValueNode) {
                    continue;
                }

                $resolvedParameterType = $classMethodParameterTypes[$parameterPosition] ?? null;
                if (! $resolvedParameterType instanceof Type) {
                    continue;
                }

                $normalizedResolvedParameterType = $this->typeNormalizer->generalizeConstantBoolTypes(
                    $resolvedParameterType
                );
                $arrayDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode(
                    $normalizedResolvedParameterType
                );

                $paramTagValueNode = new ParamTagValueNode($arrayDocTypeNode, false, '$' . $parameterName, '', false);
                $classMethodPhpDocInfo->addTagValueNode($paramTagValueNode);

                $hasChanged = true;
                $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($classMethod);
            }
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }
}
