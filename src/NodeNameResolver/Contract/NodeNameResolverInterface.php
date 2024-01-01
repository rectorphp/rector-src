<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\Contract;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

/**
 * @template TNode as Node
 */
interface NodeNameResolverInterface
{
    /**
     * @return class-string<TNode>
     */
    public function getNode(): string;

    public function resolve(Node $node, ?Scope $scope): ?string;
}
