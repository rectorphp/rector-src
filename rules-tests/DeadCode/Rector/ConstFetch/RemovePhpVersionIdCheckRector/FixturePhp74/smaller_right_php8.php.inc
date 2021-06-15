<?php

namespace Rector\Tests\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector\FixturePhp74;

class SmallerRightPhp8
{
    public function run()
    {
        // assume current PHP_VERSION_ID is php 7.4
        // as configured in constraint in configured_rule_php74.php
        if (80000 < PHP_VERSION_ID) {
            echo 'do something';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector\FixturePhp74;

class SmallerRightPhp8
{
    public function run()
    {
        echo 'do something';
    }
}

?>