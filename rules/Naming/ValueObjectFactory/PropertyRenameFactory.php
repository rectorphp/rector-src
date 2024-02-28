<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObjectFactory;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Validation\RectorAssert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class PropertyRenameFactory
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
    ) {
    }

    public function createFromExpectedName(
        ClassLike $classLike,
        Property $property,
        string $expectedName
    ): ?PropertyRename {
        $currentName = $this->nodeNameResolver->getName($property);
        $className = (string) $this->nodeNameResolver->getName($classLike);

        try {
            return new PropertyRename(
                $property,
                $expectedName,
                $currentName,
                $classLike,
                $className,
                $property->props[0]
            );
        } catch (InvalidArgumentException) {
        }

        return null;
    }
}
