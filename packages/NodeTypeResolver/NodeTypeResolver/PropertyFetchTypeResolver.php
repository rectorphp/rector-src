<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\PropertyFetchTypeResolver\PropertyFetchTypeResolverTest
 *
 * @implements NodeTypeResolverInterface<PropertyFetch>
 */
final class PropertyFetchTypeResolver implements NodeTypeResolverInterface
{
    private NodeTypeResolver $nodeTypeResolver;

    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    #[Required]
    public function autowire(NodeTypeResolver $nodeTypeResolver): void
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function resolve(Node $node, ?Scope $scope): Type
    {
        // compensate 3rd party non-analysed property reflection
        $vendorPropertyType = $this->getVendorPropertyFetchType($node, $scope);
        if (! $vendorPropertyType instanceof MixedType) {
            return $vendorPropertyType;
        }

        if (! $scope instanceof Scope) {
            return new MixedType();
        }

        return $scope->getType($node);
    }

    private function getVendorPropertyFetchType(PropertyFetch $propertyFetch, ?Scope $scope): Type
    {
        // 3rd party code
        $propertyName = $this->nodeNameResolver->getName($propertyFetch->name);
        if ($propertyName === null) {
            return new MixedType();
        }

        $varType = $this->nodeTypeResolver->getType($propertyFetch->var);
        if (! $varType instanceof ObjectType) {
            return new MixedType();
        }

        if (! $this->reflectionProvider->hasClass($varType->getClassName())) {
            return new MixedType();
        }

        $classReflection = $this->reflectionProvider->getClass($varType->getClassName());
        if (! $classReflection->hasProperty($propertyName)) {
            return new MixedType();
        }

        if (! $scope instanceof Scope) {
            return new MixedType();
        }

        $propertyReflection = $classReflection->getProperty($propertyName, $scope);

        return $propertyReflection->getReadableType();
    }
}
