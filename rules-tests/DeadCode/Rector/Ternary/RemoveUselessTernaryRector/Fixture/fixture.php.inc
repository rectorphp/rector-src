<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class Fixture
{
    public function go(bool $value)
    {
        return $value ?: false;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class Fixture
{
    public function go(bool $value)
    {
        return $value;
    }
}

?>
