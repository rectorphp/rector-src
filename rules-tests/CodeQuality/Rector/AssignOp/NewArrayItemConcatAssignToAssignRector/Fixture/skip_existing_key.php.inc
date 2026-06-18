<?php

namespace Rector\Tests\CodeQuality\Rector\AssignOp\NewArrayItemConcatAssignToAssignRector\Fixture;

class SkipExistingKey
{
    public function run($return_data, $type, $name)
    {
        $return_data[$type] .= $name;
    }
}
