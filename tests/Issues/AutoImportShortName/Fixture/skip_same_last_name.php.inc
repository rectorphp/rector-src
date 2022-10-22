<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName\Fixture;

use Rector\Core\Tests\Issues\AutoImportShortName\Source\DateTime;

class SkipSameLastName
{
    public function run(\DateTime $dateTime)
    {
    }

    public function execute(DateTime $dateTime)
    {
    }
}
