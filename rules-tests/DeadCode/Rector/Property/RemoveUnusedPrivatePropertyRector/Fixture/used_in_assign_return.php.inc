<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class UsedInAssignReturn
{
    private $count = 0;

    public function run()
    {
        return $this->count = 2;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class UsedInAssignReturn
{
    public function run()
    {
        return 2;
    }
}

?>
