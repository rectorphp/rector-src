<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class WithArrayEmpty
{
    public function go(array $value)
    {
        return $value ?: [];
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class WithArrayEmpty
{
    public function go(array $value)
    {
        return $value;
    }
}

?>
