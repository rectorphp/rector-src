<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\NodeManipulator\FuncCallManipulator;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;

final class ParamAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeComparator $nodeComparator,
        private readonly ValueResolver $valueResolver,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly FuncCallManipulator $funcCallManipulator
    ) {
    }

    public function isParamUsedInClassMethod(ClassMethod $classMethod, Param $param): bool
    {
        return (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped($classMethod, function (Node $node) use (
            $param
        ): bool {
            if (! $node instanceof Variable && ! $node instanceof Closure && ! $node instanceof FuncCall) {
                return false;
            }

            if ($node instanceof Variable) {
                return $this->nodeComparator->areNodesEqual($node, $param->var);
            }

            if ($node instanceof Closure) {
                return $this->isVariableInClosureUses($node, $param->var);
            }

            if (! $this->nodeNameResolver->isName($node, 'compact')) {
                return false;
            }

            $arguments = $this->funcCallManipulator->extractArgumentsFromCompactFuncCalls([$node]);
            return $this->nodeNameResolver->isNames($param, $arguments);
        });
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

    private function isVariableInClosureUses(Closure $closure, Variable $variable): bool
    {
        foreach ($closure->uses as $use) {
            if ($this->nodeComparator->areNodesEqual($use->var, $variable)) {
                return true;
            }
        }

        return false;
    }
}
