<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class TrueFalseOnBool
{
    public function go(bool $value)
    {
        return $value ? true : false;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class TrueFalseOnBool
{
    public function go(bool $value)
    {
        return $value;
    }
}

?>
