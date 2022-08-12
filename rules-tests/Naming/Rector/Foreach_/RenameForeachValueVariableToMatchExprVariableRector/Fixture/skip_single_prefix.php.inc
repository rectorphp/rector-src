<?php

namespace Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector\Fixture;

final class SkipSinglePrefix
{
    public function run($variable)
    {
        $array = [];
        foreach ($variable as $property) {
            $array[] = $property;
        }
    }
}
