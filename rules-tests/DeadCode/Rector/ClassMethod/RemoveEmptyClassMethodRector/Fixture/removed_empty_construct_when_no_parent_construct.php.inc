<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Source\ParentWithoutConstructor;

class RemovedEmptyConstructWhenNoParentConstruct extends ParentWithoutConstructor
{
    public function __construct()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Source\ParentWithoutConstructor;

class RemovedEmptyConstructWhenNoParentConstruct extends ParentWithoutConstructor
{
}

?>
