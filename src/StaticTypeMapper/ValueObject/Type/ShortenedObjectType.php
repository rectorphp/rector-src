<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\ValueObject\Type;

use PHPStan\Type\IsSuperTypeOfResult;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

/**
 * @api
 */
final class ShortenedObjectType extends ObjectType
{
    /**
     * @param class-string $fullyQualifiedName
     */
    public function __construct(
        string $shortName,
        private readonly string $fullyQualifiedName
    ) {
        parent::__construct($shortName);
    }

    public function isSuperTypeOf(Type $type): IsSuperTypeOfResult
    {
        $fullyQualifiedObjectType = new ObjectType($this->fullyQualifiedName);
        return $fullyQualifiedObjectType->isSuperTypeOf($type);
    }

    public function getShortName(): string
    {
        return $this->getClassName();
    }

    /**
     * @return class-string
     */
    public function getFullyQualifiedName(): string
    {
        return $this->fullyQualifiedName;
    }
}
