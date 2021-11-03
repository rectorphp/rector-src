<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObjectFactory;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @see \Rector\Tests\Naming\ValueObjectFactory\PropertyRenameFactory\PropertyRenameFactoryTest
 */
final class PropertyRenameFactory
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function createFromExpectedName(Property $property, string $expectedName): ?PropertyRename
    {
        $currentName = $this->nodeNameResolver->getName($property);

        $classLike = $this->betterNodeFinder->findParentType($property, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $scope = $property->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        $className = $scope->getClassReflection()
            ->getName();

        return new PropertyRename(
            $property,
            $expectedName,
            $currentName,
            $classLike,
            $className,
            $property->props[0]
        );
    }
}
