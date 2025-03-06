<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

use DateTimeImmutable;

final class SkipWithSideEffect
{
    public function run()
    {
        $arr = [
            new DateTimeImmutable("2025-03-10 00:00:00"),
            new DateTimeImmutable("2025-03-14 00:00:00"),
            new DateTimeImmutable("2025-03-18 00:00:00"),
            new DateTimeImmutable("2025-03-22 00:00:00"),
            new DateTimeImmutable("2025-03-26 00:00:00"),
            new DateTimeImmutable("2025-03-30 00:00:00"),
        ];
        $checkDate = new DateTimeImmutable("2025-03-20 00:00:00");
        for($foundDate = array_shift($arr); $foundDate !== null && $foundDate < $checkDate; $foundDate = array_shift($arr));

        var_dump($foundDate);
    }
}
