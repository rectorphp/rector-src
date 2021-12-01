<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;

/**
 * @implements NodeNameResolverInterface<ClassLike>
 */
final class ClassNameResolver implements NodeNameResolverInterface
{
    public function getNode(): string
    {
        return ClassLike::class;
    }

    /**
     * @param ClassLike $node
     */
    public function resolve(Node $node): ?string
    {
        return $node->namespacedName->toString();
    }
}
