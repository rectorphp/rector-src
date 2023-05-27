<?php

namespace Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\Fixture;

final class SkipAlreadyArray
{
    public function fun()
    {
        $array = [];

        $array[] = 'foo';
        $array[] = 'bar';

        return $array;
    }
}
