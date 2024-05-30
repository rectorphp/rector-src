<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

final class SomeClass
{
    public function run($a, $b)
    {
        echo $a;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

final class SomeClass
{
    public function run($a)
    {
        echo $a;
    }
}

?>
