<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\Contract;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Type;

/**
 * @template TNode as Node
 */
interface NodeTypeResolverInterface
{
    /**
     * @return array<class-string<TNode>>
     */
    public function getNodeClasses(): array;

    /**
     * @param TNode $node
     */
    public function resolve(Node $node, Scope $scope): Type;
}
