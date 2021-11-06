<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\Contract;

use PhpParser\Node;

interface NodeNameResolverInterface
{
    /**
     * @return class-string<Node>
     */
    public function getNode(): string;

    public function resolve(Node $node, \PHPStan\Analyser\Scope $scope): ?string;
}
