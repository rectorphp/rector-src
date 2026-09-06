<?php

declare(strict_types=1);

namespace Rector\Tests\Application\ApplicationFileProcessor\Source;

final class WithTwoClosuresSecond
{
    public function run(): void
    {
        $first = function () {
            return 1;
        };

        $second = function () {
            return 2;
        };
    }
}
