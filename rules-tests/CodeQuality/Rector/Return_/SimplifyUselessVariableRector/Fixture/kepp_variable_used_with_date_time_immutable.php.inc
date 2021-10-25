<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyUselessVariableRector\Fixture;

use DateTimeImmutable;

final class KeepVariableUsedWithDateTimeImmutable
{
    public function run()
    {
        $dateTime = new DateTimeImmutable();
        $dateTime = $dateTime->setTime(14, 0, 0);

        return $dateTime;
    }
}
