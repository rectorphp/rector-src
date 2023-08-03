<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector\Fixture;

final class SkipDocblock
{
    /**
     * @param null|string $value
     */
    public function run($value)
    {
        if (!$value) {
            return 'empty';
        }

        return 'full';
    }
}
