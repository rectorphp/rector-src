<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeNameResolver\NodeNameResolver;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements NodeNameResolverInterface<ClassLike>
 */
final class ClassNameResolver implements NodeNameResolverInterface
{
    private NodeNameResolver $nodeNameResolver;

    #[Required]
    public function autowire(NodeNameResolver $nodeNameResolver): void
    {
        $this->nodeNameResolver = $nodeNameResolver;
    }

    public function getNode(): string
    {
        return ClassLike::class;
    }

    /**
     * @param ClassLike $node
     */
    public function resolve(Node $node): ?string
    {
        if ($node instanceof Class_ && $node->namespacedName !== null && $node->namespacedName::class === Name::class) {
            return $node->namespacedName->toString();
        }

        if ($node->name === null) {
            return null;
        }

        return $this->nodeNameResolver->getName($node->name);
    }
}
