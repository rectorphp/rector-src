<?php

namespace Rector\Tests\CodeQuality\Rector\Name\FixClassCaseSensitivityNameRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Name\FixClassCaseSensitivityNameRector\Source\MissCaseTypedClass;

final class SkipUnionParamType
{
    public function run(string|misscasetypedclass $misscasetypedclass)
    {
        return $misscasetypedclass;
    }
}

