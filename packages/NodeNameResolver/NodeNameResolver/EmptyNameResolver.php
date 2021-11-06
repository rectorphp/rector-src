<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\Empty_;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;

final class EmptyNameResolver implements NodeNameResolverInterface
{
    /**
     * @return class-string<Node>
     */
    public function getNode(): string
    {
        return Empty_::class;
    }

    /**
     * @param Empty_ $node
     */
    public function resolve(Node $node, \PHPStan\Analyser\Scope $scope): ?string
    {
        return 'empty';
    }
}
