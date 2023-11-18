<?php

namespace Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\Fixture;

final class SkipAssignedNotArray
{
    public function fun()
    {
        $condition = '';
        if (random_int(0,2)) {
            $condition = 'id NOT IN (123)';
        }
        $this->doFoo($condition);

        $condition = [];
        $condition['typ'] = 'select';
        if (random_int(0,1)) {
            $condition[] = 'id NOT IN (456)';
        }

        $this->doFoo($condition);
    }

    private function doFoo($condition)
    {}
}
