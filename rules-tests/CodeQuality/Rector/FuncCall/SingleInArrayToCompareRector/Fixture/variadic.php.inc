<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector\Fixture;

final class DemoFile
{
    public function run()
    {
        $a = ['a', 'b'];
        echo in_array('a', [...$a]);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector\Fixture;

final class DemoFile
{
    public function run()
    {
        $a = ['a', 'b'];
        echo in_array('a', [...$a]);
    }
}

?>
