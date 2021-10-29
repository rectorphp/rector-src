<?php

namespace Rector\Tests\CodeQuality\Rector\Name\FixClassCaseSensitivityNameRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Name\FixClassCaseSensitivityNameRector\Source\MissCaseTypedClass;

final class SkipCorrectClassConstFetch
{
    public function run()
    {
        $fatherSon = MissCaseTypedClass::SOME_CONST;
    }
}
