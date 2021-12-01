<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeNameResolver\NodeNameResolver;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements NodeNameResolverInterface<ClassLike>
 */
final class ClassNameResolver implements NodeNameResolverInterface
{
    private NodeNameResolver $nodeNameResolver;

    private const ALLOWED_CLASSLIKE = [
        Class_::class,
        Interface_::class,
        Trait_::class,
    ];

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
        if (in_array($node::class, self::ALLOWED_CLASSLIKE, true) && property_exists(
            $node,
            'namespacedName'
        ) && $node->namespacedName !== null && $node->namespacedName::class === Name::class) {
            return $node->namespacedName->toString();
        }

        if ($node->name === null) {
            return null;
        }

        return $this->nodeNameResolver->getName($node->name);
    }
}
