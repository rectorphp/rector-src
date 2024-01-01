<?php

declare(strict_types=1);

namespace Rector\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeTraverser;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class PropertyFetchAssignManipulator
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly PropertyFetchAnalyzer $propertyFetchAnalyzer
    ) {
    }

    public function isAssignedMultipleTimesInConstructor(Class_ $class, Property $property): bool
    {
        $classMethod = $class->getMethod(MethodName::CONSTRUCT);
        if (! $classMethod instanceof ClassMethod) {
            return false;
        }

        $count = 0;
        $propertyName = $this->nodeNameResolver->getName($property);

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $classMethod->getStmts(),
            function (Node $node) use ($propertyName, &$count): ?int {
                // skip anonymous classes and inner function
                if ($node instanceof Class_ || $node instanceof Function_) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if (! $node instanceof Assign) {
                    return null;
                }

                if (! $this->propertyFetchAnalyzer->isLocalPropertyFetchName($node->var, $propertyName)) {
                    return null;
                }

                ++$count;

                if ($count === 2) {
                    return NodeTraverser::STOP_TRAVERSAL;
                }

                return null;
            }
        );

        return $count === 2;
    }
}
