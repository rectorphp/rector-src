<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

final class KeepDate
{
    private function getItemByRefOrNull(?\DateTime $startDate)
    {
    }
}
