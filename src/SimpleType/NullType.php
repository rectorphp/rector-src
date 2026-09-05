<?php

declare(strict_types=1);

namespace Rector\SimpleType;

use Rector\SimpleType\Contract\SimpleTypeInterface;

final class NullType implements SimpleTypeInterface
{
    public function describe(): string
    {
        return 'null';
    }
}
