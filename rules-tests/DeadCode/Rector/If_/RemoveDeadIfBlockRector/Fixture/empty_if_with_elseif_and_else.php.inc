<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class EmptyIfWithElseIfAndElse
{
    public function calculatePrice($qty, $cond, $price1, $price2)
    {
        if ($qty < 1) {
        } elseif (!$cond) {
            $total = $qty * $price1;
        } else {
            $total = $qty * $price2;
        }

        return $total;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class EmptyIfWithElseIfAndElse
{
    public function calculatePrice($qty, $cond, $price1, $price2)
    {
        if ($qty >= 1 && !$cond) {
            $total = $qty * $price1;
        } else {
            $total = $qty * $price2;
        }

        return $total;
    }
}

?>
