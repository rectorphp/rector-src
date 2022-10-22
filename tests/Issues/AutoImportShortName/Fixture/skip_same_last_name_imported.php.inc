<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName\Fixture;

use Rector\Core\Tests\Issues\AutoImportShortName\Source\DateTime;

class SkipSameLastNameImported
{
    public function run(\DateTime $dateTime)
    {
    }

    public function execute(DateTime $dateTime)
    {
    }
}
