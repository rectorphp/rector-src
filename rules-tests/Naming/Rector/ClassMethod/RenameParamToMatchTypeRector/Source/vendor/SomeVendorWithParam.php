<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Source\vendor;

use Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Source\EliteManager;

final class SomeVendorWithParam
{
    public function run(EliteManager $eventManager)
    {
    }
}
