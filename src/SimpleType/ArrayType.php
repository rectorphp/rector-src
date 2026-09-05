<?php

declare(strict_types=1);

namespace Rector\SimpleType;

use Rector\SimpleType\Contract\SimpleTypeInterface;

final class ArrayType implements SimpleTypeInterface
{
    public function describe(): string
    {
        return 'array';
    }
}
