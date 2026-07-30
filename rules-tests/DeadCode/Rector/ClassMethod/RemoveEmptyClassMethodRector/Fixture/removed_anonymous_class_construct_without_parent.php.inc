<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

function createPlain()
{
    return new class {
        public function __construct()
        {
        }
    };
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

function createPlain()
{
    return new class
    {
    };
}

?>
