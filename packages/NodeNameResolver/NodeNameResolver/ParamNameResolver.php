<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Param;
use PHPStan\Analyser\Scope;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeNameResolver\NodeNameResolver;

/**
 * @implements NodeNameResolverInterface<Param>
 */
final class ParamNameResolver implements NodeNameResolverInterface
{
    public function getNode(): string
    {
        return Param::class;
    }

    /**
     * @param Param $node
     */
    public function resolve(Node $node, ?Scope $scope): ?string
    {
        if ($node->var instanceof Error) {
            return null;
        }

        if ($node->var->name instanceof Expr) {
            return null;
        }

        return $node->var->name;
    }
}
