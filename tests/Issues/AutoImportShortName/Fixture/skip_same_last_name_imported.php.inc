<?php

namespace Rector\Tests\Issues\AutoImportShortName\Fixture;

use Rector\Tests\Issues\AutoImportShortName\Source\DateTime;

class SkipSameLastNameImported
{
    public function run(\DateTime $dateTime)
    {
    }

    public function execute(DateTime $dateTime)
    {
    }
}
