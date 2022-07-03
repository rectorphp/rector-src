<?php

namespace Rector\Tests\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector\Fixture;

final class MirrorComment
{
    public function run($value)
    {
        if ($value === 50) {
            // some comment
            $value = 100;
        }

        return $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector\Fixture;

final class MirrorComment
{
    public function run($value)
    {
        if ($value === 50) {
            // some comment
            return 100;
        }

        return $value;
    }
}

?>
