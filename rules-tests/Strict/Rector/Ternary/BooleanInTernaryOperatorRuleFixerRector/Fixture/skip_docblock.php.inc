<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\Ternary\BooleanInTernaryOperatorRuleFixerRector\Fixture;

final class SkipDocblock
{
    /**
     * @param array $data
     */
    public function run($data)
    {
        return $data ? 1 : 2;
    }
}
