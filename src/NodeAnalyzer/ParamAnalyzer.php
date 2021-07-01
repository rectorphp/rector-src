<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use PhpParser\Node\NullableType;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class ParamAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeComparator $nodeComparator,
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function isParamUsedInClassMethod(ClassMethod $classMethod, Param $param): bool
    {
        return (bool) $this->betterNodeFinder->findFirst((array) $classMethod->stmts, function (Node $node) use (
            $param
        ): bool {
            if (! $node instanceof Variable) {
                return false;
            }

            return $this->nodeComparator->areNodesEqual($node, $param->var);
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

        $type = $this->nodeTypeResolver->resolve($param->var);
        return $type instanceof NullableType;
    }
}
