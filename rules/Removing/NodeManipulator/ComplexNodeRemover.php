<?php

declare(strict_types=1);

namespace Rector\Removing\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeRemoval\AssignRemover;
use Rector\NodeRemoval\NodeRemover;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ComplexNodeRemover
{
    public function __construct(
        private readonly AssignRemover $assignRemover,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeRemover $nodeRemover,
        private readonly NodeComparator $nodeComparator,
    ) {
    }

    /**
     * @deprecated
     *
     * @param Param[] $params
     * @param int[] $paramKeysToBeRemoved
     * @return int[]
     */
    public function processRemoveParamWithKeys(array $params, array $paramKeysToBeRemoved): array
    {
        $totalKeys = count($params) - 1;
        $removedParamKeys = [];

        foreach ($paramKeysToBeRemoved as $paramKeyToBeRemoved) {
            $startNextKey = $paramKeyToBeRemoved + 1;
            for ($nextKey = $startNextKey; $nextKey <= $totalKeys; ++$nextKey) {
                if (! isset($params[$nextKey])) {
                    // no next param, break the inner loop, remove the param
                    break;
                }

                if (in_array($nextKey, $paramKeysToBeRemoved, true)) {
                    // keep searching next key not in $paramKeysToBeRemoved
                    continue;
                }

                return [];
            }

            $this->nodeRemover->removeNode($params[$paramKeyToBeRemoved]);
            $removedParamKeys[] = $paramKeyToBeRemoved;
        }

        return $removedParamKeys;
    }

    /**
     * @param Assign[] $assigns
     */
    public function removePropertyAssigns(array $assigns): void
    {
        foreach ($assigns as $assign) {
            // remove assigns
            $this->assignRemover->removeAssignNode($assign);
            $this->removeConstructorDependency($assign);
        }
    }

    private function removeConstructorDependency(Assign $assign): void
    {
        $classMethod = $this->betterNodeFinder->findParentType($assign, ClassMethod::class);
        if (! $classMethod instanceof  ClassMethod) {
            return;
        }

        if (! $this->nodeNameResolver->isName($classMethod, MethodName::CONSTRUCT)) {
            return;
        }

        $class = $this->betterNodeFinder->findParentType($assign, Class_::class);
        if (! $class instanceof Class_) {
            return;
        }

        $constructClassMethod = $class->getMethod(MethodName::CONSTRUCT);
        if (! $constructClassMethod instanceof ClassMethod) {
            return;
        }

        $params = $constructClassMethod->getParams();
        $paramKeysToBeRemoved = [];

        /** @var Variable[] $variables */
        $variables = $this->resolveVariables($constructClassMethod);
        foreach ($params as $key => $param) {
            $variable = $this->betterNodeFinder->findFirst(
                (array) $constructClassMethod->stmts,
                fn (Node $node): bool => $this->nodeComparator->areNodesEqual($param->var, $node)
            );

            if (! $variable instanceof Node) {
                continue;
            }

            if ($this->isExpressionVariableNotAssign($variable)) {
                continue;
            }

            if (! $this->nodeComparator->areNodesEqual($param->var, $assign->expr)) {
                continue;
            }

            if ($this->isInVariables($variables, $assign)) {
                continue;
            }

            $paramKeysToBeRemoved[] = $key;
        }

        $this->processRemoveParamWithKeys($params, $paramKeysToBeRemoved);
    }

    /**
     * @return Variable[]
     */
    private function resolveVariables(ClassMethod $classMethod): array
    {
        return $this->betterNodeFinder->find(
            (array) $classMethod->stmts,
            function (Node $subNode): bool {
                if (! $subNode instanceof Variable) {
                    return false;
                }

                return $this->isExpressionVariableNotAssign($subNode);
            }
        );
    }

    /**
     * @param Variable[] $variables
     */
    private function isInVariables(array $variables, Assign $assign): bool
    {
        foreach ($variables as $variable) {
            if ($this->nodeComparator->areNodesEqual($assign->expr, $variable)) {
                return true;
            }
        }

        return false;
    }

    private function isExpressionVariableNotAssign(Node $node): bool
    {
        $expressionVariable = $node->getAttribute(AttributeKey::PARENT_NODE);
        return ! $expressionVariable instanceof Assign;
    }
}
