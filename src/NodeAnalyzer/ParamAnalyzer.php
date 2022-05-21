<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\Value\ValueResolver;

final class ParamAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeComparator $nodeComparator,
        private readonly ValueResolver $valueResolver
    ) {
    }

    public function isParamUsedInClassMethod(ClassMethod $classMethod, Param $param): bool
    {
        return (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped($classMethod, function (Node $node) use (
            $param
        ): bool {
            if (! $node instanceof Variable && ! $node instanceof Closure) {
                return false;
            }

            if ($node instanceof Variable) {
                return $this->nodeComparator->areNodesEqual($node, $param->var);
            }

            foreach ($node->uses as $use) {
                if ($this->nodeComparator->areNodesEqual($use->var, $param->var)) {
                    return true;
                }
            }

            return false;
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
}
