<?php

namespace Rector\Tests\Arguments\Rector\FuncCall\SwapFuncCallArgumentsRector\Fixture;

final class DemoFile
{
    public function run()
    {
        return transLoc(
                $one,
                $two,
                $three
            );
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\FuncCall\SwapFuncCallArgumentsRector\Fixture;

final class DemoFile
{
    public function run()
    {
        return transLoc(
                $one,
                $three,
                $two
            );
    }
}

?>
