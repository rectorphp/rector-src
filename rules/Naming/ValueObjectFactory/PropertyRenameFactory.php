<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObjectFactory;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\NodeNameResolver\NodeNameResolver;

final class PropertyRenameFactory
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function createFromExpectedName(Property $property, string $expectedName): ?PropertyRename
    {
        $currentName = $this->nodeNameResolver->getName($property);

        $classLike = $this->betterNodeFinder->findParentType($property, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $className = (string) $this->nodeNameResolver->getName($classLike);

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
