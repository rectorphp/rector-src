<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Source\EliteManager;
use Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Source\vendor\SomeVendorWithParam;

class SkipOverrideVendorParam extends SomeVendorWithParam
{
    public function run(EliteManager $eventManager)
    {
    }
}
