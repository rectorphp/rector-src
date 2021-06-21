<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

final class DemoFile
{
    public function hasMXRecords()
    {
        $mxrecords = [];

        getmxrr('getrector.org', $mxrecords);

        return count($mxrecords) === 0;
    }
}
?>
