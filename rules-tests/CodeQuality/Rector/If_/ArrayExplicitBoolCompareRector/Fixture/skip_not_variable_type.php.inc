<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ArrayExplicitBoolCompareRector\Fixture;

final class SkipNotVariableType
{
    public function run(\stdClass $data)
    {
        if ($data->SomeProperty) {
        }
    }
}
?>
