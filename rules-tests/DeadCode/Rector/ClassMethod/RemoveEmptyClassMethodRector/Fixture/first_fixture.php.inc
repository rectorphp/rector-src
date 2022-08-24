<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

final class FirstFixture
{
    public function __construct()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

final class FirstFixture
{
}

?>
