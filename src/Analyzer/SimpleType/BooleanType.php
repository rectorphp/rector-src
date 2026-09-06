<?php

declare(strict_types=1);

namespace Rector\Analyzer\SimpleType;

use Rector\Analyzer\SimpleType\Contract\SimpleTypeInterface;

final class BooleanType implements SimpleTypeInterface
{
    public function describe(): string
    {
        return 'bool';
    }
}
