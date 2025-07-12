<?php

namespace Rector\Tests\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector\Fixture;

class EmptyContent
{
    public function run()
    {
        if (true) {
        } else {
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector\Fixture;

class EmptyContent
{
    public function run()
    {
    }
}

?>
