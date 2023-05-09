<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PHPStan\Analyser\Scope;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeNameResolver\NodeNameResolver;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements NodeNameResolverInterface<ClassConstFetch>
 */
final class ClassConstFetchNameResolver implements NodeNameResolverInterface
{
    private NodeNameResolver $nodeNameResolver;

    #[Required]
    public function autowire(NodeNameResolver $nodeNameResolver): void
    {
        $this->nodeNameResolver = $nodeNameResolver;
    }

    public function getNode(): string
    {
        return ClassConstFetch::class;
    }

    /**
     * @param ClassConstFetch $node
     */
    public function resolve(Node $node, ?Scope $scope): ?string
    {
        $class = $this->nodeNameResolver->getName($node->class);
        $name = $this->nodeNameResolver->getName($node->name);
        if ($class === null) {
            return null;
        }

        if ($name === null) {
            return null;
        }

        return $class . '::' . $name;
    }
}
