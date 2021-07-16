<?php

namespace Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\Fixture;

final class EmptyStringArrayDimFetchValue
{
    public function run()
    {
        $r = [];
        $r[] = '';
    }
}

?>
