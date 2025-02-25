<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Source\EliteManager;

final class SkipVariadic
{
    public function __construct(EliteManager ...$eliteManagers)
    {
    }
}
