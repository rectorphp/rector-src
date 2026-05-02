<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\SplitDoubleAssignRector\Fixture;

class MirrorComment
{
    public function run()
    {
        // some comment
        $one = $two = 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\SplitDoubleAssignRector\Fixture;

class MirrorComment
{
    public function run()
    {
        // some comment
        $one = 1;
        $two = 1;
    }
}

?>
