<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\SplitDoubleAssignRector\Fixture;

final class DemoFile
{
    public function run()
    {
        $foo = $bar = $hello = $world = '';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\SplitDoubleAssignRector\Fixture;

final class DemoFile
{
    public function run()
    {
        $foo = '';
        $bar = '';
        $hello = '';
        $world = '';
    }
}

?>
