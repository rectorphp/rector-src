<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\ParamTypeInferer;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\Core\NodeManipulator\PropertyFetchAssignManipulator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\TypeDeclaration\Contract\TypeInferer\ParamTypeInfererInterface;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class GetterNodeParamTypeInferer implements ParamTypeInfererInterface
{
    public function __construct(
        private PropertyFetchAssignManipulator $propertyFetchAssignManipulator,
        private PropertyFetchAnalyzer $propertyFetchAnalyzer,
        private PhpDocInfoFactory $phpDocInfoFactory,
        private NodeNameResolver $nodeNameResolver,
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function inferParam(Param $param): Type
    {
        $class = $this->betterNodeFinder->findParentType($param, Class_::class);
        if (! $class instanceof Class_) {
            return new MixedType();
        }

        /** @var ClassMethod $classMethod */
        $classMethod = $param->getAttribute(AttributeKey::PARENT_NODE);

        /** @var string $paramName */
        $paramName = $this->nodeNameResolver->getName($param);

        $propertyNames = $this->propertyFetchAssignManipulator->getPropertyNamesOfAssignOfVariable(
            $classMethod,
            $paramName
        );
        if ($propertyNames === []) {
            return new MixedType();
        }

        $returnType = new MixedType();

        // resolve property assigns
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($class, function (Node $node) use (
            $propertyNames,
            &$returnType
        ): ?int {
            if (! $node instanceof Return_) {
                return null;
            }

            if ($node->expr === null) {
                return null;
            }

            $isMatch = $this->propertyFetchAnalyzer->isLocalPropertyOfNames($node->expr, $propertyNames);
            if (! $isMatch) {
                return null;
            }

            // what is return type?
            $classMethod = $node->getAttribute(AttributeKey::METHOD_NODE);
            if (! $classMethod instanceof ClassMethod) {
                return null;
            }

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);

            $methodReturnType = $phpDocInfo->getReturnType();
            if ($methodReturnType instanceof MixedType) {
                return null;
            }

            $returnType = $methodReturnType;

            return NodeTraverser::STOP_TRAVERSAL;
        });

        return $returnType;
    }
}
