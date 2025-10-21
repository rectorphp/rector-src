<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlocksRector\Fixture;

class RemoveDeadIfBlock
{
    public function run($condition)
    {
        if ($value) {
        } elseif ($differentValue) {
        } else {
        }

        if ($differentValue) {
            echo 'different';
        } elseif ($value) {
        } else {
        }

        if ($differentValue) {
        } elseif ($value) {
            echo 'value';
        } else {
        }

        return $differentValue;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlocksRector\Fixture;

class RemoveDeadIfBlock
{
    public function run($condition)
    {
        if ($differentValue) {
            echo 'different';
        }

        if (!$differentValue && $value) {
            echo 'value';
        }

        return $differentValue;
    }
}

?>
