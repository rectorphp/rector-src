<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class WithIfEqual
{
    public function go(bool $value)
    {
        return $value ? $value : false;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class WithIfEqual
{
    public function go(bool $value)
    {
        return $value;
    }
}

?>
