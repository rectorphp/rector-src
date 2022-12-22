<?php

namespace Rector\Tests\DeadCode\Rector\Class_\TargetRemoveClassMethodRector\Fixture;

class SomeClass
{
    public function removeMe()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Class_\TargetRemoveClassMethodRector\Fixture;

class SomeClass
{
}

?>
