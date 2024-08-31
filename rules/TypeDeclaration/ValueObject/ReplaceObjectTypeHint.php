<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PHPStan\Type\ObjectType;

final readonly class ReplaceObjectTypeHint
{
    public function __construct(
        private ObjectType $originalType,
        private ObjectType $replaceType,
    ) {
    }

    public function getOriginalObjectType(): ObjectType
    {
        return $this->originalType;
    }

    public function getReplacingObjectType(): ObjectType
    {
        return $this->replaceType;
    }
}
