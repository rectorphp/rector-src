<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use Rector\Core\NodeManipulator\FuncCallManipulator;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class ParamAnalyzer
{
    public function __construct(
        private readonly NodeComparator $nodeComparator,
        private readonly ValueResolver $valueResolver,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly FuncCallManipulator $funcCallManipulator,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function isParamUsedInClassMethod(ClassMethod $classMethod, Param $param): bool
    {
        $isParamUsed = false;

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($classMethod->stmts, function (Node $node) use (
            &$isParamUsed,
            $param
        ): ?int {
            if ($isParamUsed) {
                return NodeTraverser::STOP_TRAVERSAL;
            }

            if ($this->isUsedAsArg($node, $param)) {
                $isParamUsed = true;
            }

            // skip nested anonymous class
            if ($node instanceof Class_ || $node instanceof Function_) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if ($node instanceof Variable && $this->nodeComparator->areNodesEqual($node, $param->var)) {
                $isParamUsed = true;
            }

            if ($node instanceof Closure && $this->isVariableInClosureUses($node, $param->var)) {
                $isParamUsed = true;
            }

            if ($this->isParamUsed($node, $param)) {
                $isParamUsed = true;
            }

            return null;
        });

        return $isParamUsed;
    }

    /**
     * @param Param[] $params
     */
    public function hasPropertyPromotion(array $params): bool
    {
        foreach ($params as $param) {
            if ($param->flags !== 0) {
                return true;
            }
        }

        return false;
    }

    public function isNullable(Param $param): bool
    {
        if ($param->variadic) {
            return false;
        }

        if ($param->type === null) {
            return false;
        }

        return $param->type instanceof NullableType;
    }

    public function hasDefaultNull(Param $param): bool
    {
        return $param->default instanceof ConstFetch && $this->valueResolver->isNull($param->default);
    }

    public function isParamReassign(Param $param): bool
    {
        $classMethod = $param->getAttribute(AttributeKey::PARENT_NODE);

        if (! $classMethod instanceof ClassMethod) {
            return false;
        }

        $paramName = (string) $this->nodeNameResolver->getName($param->var);
        return (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped($classMethod, function (Node $node) use (
            $paramName
        ): bool {
            if (! $node instanceof Assign) {
                return false;
            }

            if (! $node->var instanceof Variable) {
                return false;
            }

            return $this->nodeNameResolver->isName($node->var, $paramName);
        });
    }

    private function isVariableInClosureUses(Closure $closure, Variable $variable): bool
    {
        foreach ($closure->uses as $use) {
            if ($this->nodeComparator->areNodesEqual($use->var, $variable)) {
                return true;
            }
        }

        return false;
    }

    private function isUsedAsArg(Node $node, Param $param): bool
    {
        if ($node instanceof New_ || $node instanceof CallLike) {
            if ($node->isFirstClassCallable()) {
                return false;
            }

            foreach ($node->getArgs() as $arg) {
                if ($this->nodeComparator->areNodesEqual($param->var, $arg->value)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isParamUsed(Node $node, Param $param): bool
    {
        if (! $node instanceof FuncCall) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($node, 'compact')) {
            return false;
        }

        $arguments = $this->funcCallManipulator->extractArgumentsFromCompactFuncCalls([$node]);
        return $this->nodeNameResolver->isNames($param, $arguments);
    }
}
