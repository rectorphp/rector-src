<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName\Fixture;

class SkipSameLastNameNotImported
{
    public function run(\DateTime $dateTime)
    {
    }

    public function execute(\Rector\Core\Tests\Issues\AutoImportShortName\Source\DateTime $dateTime)
    {
    }
}
