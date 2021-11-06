<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeNameResolver\NodeNameResolver;
use Symfony\Contracts\Service\Attribute\Required;

final class PropertyNameResolver implements NodeNameResolverInterface
{
    private NodeNameResolver $nodeNameResolver;

    #[Required]
    public function autowirePropertyNameResolver(NodeNameResolver $nodeNameResolver): void
    {
        $this->nodeNameResolver = $nodeNameResolver;
    }

    /**
     * @return class-string<Node>
     */
    public function getNode(): string
    {
        return Property::class;
    }

    /**
     * @param Property $node
     */
    public function resolve(Node $node, \PHPStan\Analyser\Scope $scope): ?string
    {
        if ($node->props === []) {
            return null;
        }

        $onlyProperty = $node->props[0];

        return $this->nodeNameResolver->getName($onlyProperty);
    }
}
