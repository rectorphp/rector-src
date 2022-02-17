<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

class SkipTypeByVarDoc {
    public function test () {
        /**
        * @var int $notInt
        */
        $notInt = '';
        if ($notInt) {}
    }
}
?>
-----
