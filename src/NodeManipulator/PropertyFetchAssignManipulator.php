<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeTraverser;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class PropertyFetchAssignManipulator
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly PropertyFetchAnalyzer $propertyFetchAnalyzer
    ) {
    }

    public function isAssignedMultipleTimesInConstructor(Property $property): bool
    {
        $classLike = $this->betterNodeFinder->findParentType($property, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return false;
        }

        $classMethod = $classLike->getMethod(MethodName::CONSTRUCT);
        if (! $classMethod instanceof ClassMethod) {
            return false;
        }

        $count = 0;
        $propertyName = $this->nodeNameResolver->getName($property);

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $classMethod->getStmts(),
            function (Node $node) use ($propertyName, $classLike, &$count): ?int {
                if (! $node instanceof Assign) {
                    return null;
                }

                if (! $this->propertyFetchAnalyzer->isLocalPropertyFetchName($node->var, $propertyName)) {
                    return null;
                }

                $parentClassLike = $this->betterNodeFinder->findParentType($node, ClassLike::class);
                if ($parentClassLike !== $classLike) {
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

    /**
     * @return string[]
     */
    public function getPropertyNamesOfAssignOfVariable(Node $node, string $paramName): array
    {
        $propertyNames = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($node, function (Node $node) use (
            $paramName,
            &$propertyNames
        ) {
            if (! $node instanceof Assign) {
                return null;
            }

            if (! $this->isVariableAssignToThisPropertyFetch($node, $paramName)) {
                return null;
            }

            /** @var Assign $node */
            $propertyName = $this->nodeNameResolver->getName($node->expr);
            if (is_string($propertyName)) {
                $propertyNames[] = $propertyName;
            }

            return null;
        });

        return $propertyNames;
    }

    /**
     * Matches:
     * "$this->someValue = $<variableName>;"
     */
    private function isVariableAssignToThisPropertyFetch(Assign $assign, string $variableName): bool
    {
        if (! $assign->expr instanceof Variable) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($assign->expr, $variableName)) {
            return false;
        }

        if (! $assign->var instanceof PropertyFetch) {
            return false;
        }

        $propertyFetch = $assign->var;

        // must be local property
        return $this->nodeNameResolver->isName($propertyFetch->var, 'this');
    }
}
