<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

class RemovedEmptyConstructWhenNoParent extends ParentWithoutConstruct
{
    public function __construct()
    {
    }
}

abstract class ParentWithoutConstruct
{
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

class RemovedEmptyConstructWhenNoParent extends ParentWithoutConstruct
{
}

abstract class ParentWithoutConstruct
{
}

?>
