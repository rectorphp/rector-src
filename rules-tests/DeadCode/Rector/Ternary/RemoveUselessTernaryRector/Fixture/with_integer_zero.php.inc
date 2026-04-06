<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class WithIntegerZero
{
    public function go(int $value)
    {
        return $value ?: 0;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class WithIntegerZero
{
    public function go(int $value)
    {
        return $value;
    }
}

?>
