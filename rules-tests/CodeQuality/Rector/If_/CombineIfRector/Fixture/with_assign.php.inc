<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CombineIfRector\Fixture;

final class WithAssign
{
    public function run()
    {
        if ($cond1) {
            if (($cond2 = $this->getCond2Value()) === null) {
                return 'foo';
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CombineIfRector\Fixture;

final class WithAssign
{
    public function run()
    {
        if ($cond1 && ($cond2 = $this->getCond2Value()) === null) {
            return 'foo';
        }
    }
}

?>
