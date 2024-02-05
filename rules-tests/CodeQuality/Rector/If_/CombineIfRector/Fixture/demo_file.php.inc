<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CombineIfRector\Fixture;

final class DemoFile
{
    public function run(int $param, int $param_two)
    {
        if ($param > 0) {
            if ($param_two > 0) { ?>
                hello
            <?php }
        }
    }
}
?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CombineIfRector\Fixture;

final class DemoFile
{
    public function run(int $param, int $param_two)
    {
        if ($param > 0 && $param_two > 0) { ?>
            hello
        <?php }
    }
}
?>
