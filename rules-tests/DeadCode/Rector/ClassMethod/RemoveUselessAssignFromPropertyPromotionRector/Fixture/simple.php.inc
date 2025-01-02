<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotion\Fixture;

class Simple
{
    public function __construct(private \stdClass $std)
    {
    	$this->std = $std;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotion\Fixture;

class Simple
{
    public function __construct(private \stdClass $std)
    {
    }
}

?>
