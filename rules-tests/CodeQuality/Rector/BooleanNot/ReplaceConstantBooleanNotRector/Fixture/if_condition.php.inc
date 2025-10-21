<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\ReplaceConstantBooleanNotRector\Fixture;

class IfCondition
{
    public function run()
    {
        if (!true) {
            return true;
        }
        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\ReplaceConstantBooleanNotRector\Fixture;

class IfCondition
{
    public function run()
    {
        if (false) {
            return true;
        }
        return false;
    }
}

?>
