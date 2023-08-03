<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\Ternary\DisallowedShortTernaryRuleFixerRector\Fixture;

final class SkipDocblock
{
    /**
     * @param array $array
     */
    public function run($array)
    {
        return $array ?: 2;
    }
}
