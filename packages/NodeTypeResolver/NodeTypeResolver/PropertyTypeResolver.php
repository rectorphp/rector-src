<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\PropertyTypeResolver\PropertyTypeResolverTest
 *
 * @implements NodeTypeResolverInterface<Property>
 */
final class PropertyTypeResolver implements NodeTypeResolverInterface
{
    public function __construct(
        private readonly PropertyFetchTypeResolver $propertyFetchTypeResolver
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function resolve(Node $node, ?Scope $scope): Type
    {
        // fake property to local PropertyFetch → PHPStan understands that
        $propertyFetch = new PropertyFetch(new Variable('this'), (string) $node->props[0]->name);
        $propertyFetch->setAttribute(AttributeKey::SCOPE, $scope);

        return $this->propertyFetchTypeResolver->resolve($propertyFetch);
    }
}
