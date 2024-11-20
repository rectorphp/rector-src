<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObject;

use PhpParser\Node\PropertyItem;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use Rector\Validation\RectorAssert;

final readonly class PropertyRename
{
    public function __construct(
        private Property $property,
        private string $expectedName,
        private string $currentName,
        private ClassLike $classLike,
        private string $classLikeName,
        private PropertyItem $propertyItem
    ) {
        // name must be valid
        RectorAssert::propertyName($currentName);
        RectorAssert::propertyName($expectedName);
    }

    public function getProperty(): Property
    {
        return $this->property;
    }

    public function isPrivateProperty(): bool
    {
        return $this->property->isPrivate();
    }

    public function getExpectedName(): string
    {
        return $this->expectedName;
    }

    public function getCurrentName(): string
    {
        return $this->currentName;
    }

    public function isAlreadyExpectedName(): bool
    {
        return $this->currentName === $this->expectedName;
    }

    public function getClassLike(): ClassLike
    {
        return $this->classLike;
    }

    public function getClassLikeName(): string
    {
        return $this->classLikeName;
    }

    public function getPropertyProperty(): PropertyItem
    {
        return $this->propertyItem;
    }
}
