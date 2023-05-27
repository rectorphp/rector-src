<?php

namespace Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\Fixture;

final class SkipDirectStringAssign
{
    public function fun()
    {
        $array = '';

        $array = 'foo';

        return $array;
    }
}
