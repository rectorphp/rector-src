<?php

namespace Rector\Tests\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector\FixturePhp74;

class SkipSmallerLeftPhp8
{
    public function run()
    {
        // assume current PHP_VERSION_ID is php 7.4
        // as configured in constraint in configured_rule_php74.php
        if (PHP_VERSION_ID < 80000) {
            return;
        }
        echo 'do something';
    }
}

?>
