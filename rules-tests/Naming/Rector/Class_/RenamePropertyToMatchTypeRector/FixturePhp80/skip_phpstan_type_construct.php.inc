<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObject;

use PHPStan\Type\Type;

final class SkipPHPStanTypeConstruct
{
    public function __construct(
        private Type $returnType
    ) {
    }

    public function getReturnType(): Type
    {
        return $this->returnType;
    }
}
