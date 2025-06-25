<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class BreakOnSwitch
{
    public function run($var) {
        switch($var) {
            case 'test':
                goto result;
            default:
                return false;
        }

        result:
            echo 'test';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class BreakOnSwitch
{
    public function run($var) {
        switch($var) {
            case 'test':
                goto result;
            default:
                return false;
        }

        result:
            echo 'test';
            return null;
    }
}

?>
