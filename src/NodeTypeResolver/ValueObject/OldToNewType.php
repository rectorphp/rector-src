<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\ValueObject;

use PHPStan\Type\Type;

final readonly class OldToNewType
{
    public function __construct(
        private Type $oldType,
        private Type $newType
    ) {
    }

    public function getOldType(): Type
    {
        return $this->oldType;
    }

    public function getNewType(): Type
    {
        return $this->newType;
    }
}
