<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

final class Simple
{
    public function __construct()
    {
    }

    public function boom()
    {
        // intentionally
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

final class Simple
{
    public function boom()
    {
        // intentionally
    }
}

?>
