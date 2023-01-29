<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PHPStan\Type\Type;

final class TernaryIfElseTypes
{
    public function __construct(
        private readonly Type $firstType,
        private readonly Type $secondType,
    ) {
    }

    public function getFirstType(): Type
    {
        return $this->firstType;
    }

    public function getSecondType(): Type
    {
        return $this->secondType;
    }
}
