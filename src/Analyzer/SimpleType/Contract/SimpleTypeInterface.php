<?php

declare(strict_types=1);

namespace Rector\Analyzer\SimpleType\Contract;

// PHPStan-free type contract, resolved by SimpleScope
interface SimpleTypeInterface
{
    public function describe(): string;
}
