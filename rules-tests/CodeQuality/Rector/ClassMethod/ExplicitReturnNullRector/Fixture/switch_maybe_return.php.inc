<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

class SwitchMaybeReturn
{
    public function run(int $number)
    {
        switch ($number) {
            case 1:
                return 1;
            case 2:
            default:
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

class SwitchMaybeReturn
{
    public function run(int $number)
    {
        switch ($number) {
            case 1:
                return 1;
            case 2:
            default:
        }
        return null;
    }
}

?>
