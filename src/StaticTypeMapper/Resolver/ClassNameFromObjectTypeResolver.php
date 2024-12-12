<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\Resolver;

use PHPStan\Type\Type;

final class ClassNameFromObjectTypeResolver
{
    /**
     * @return class-string|null
     */
    public static function resolve(Type $type): ?string
    {
        /** @var array<class-string> $objectClassNames */
        $objectClassNames = $type->getObjectClassNames();

        if (count($objectClassNames) !== 1) {
            return null;
        }

        return $objectClassNames[0];
    }
}
