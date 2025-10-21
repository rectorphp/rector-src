<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlocksRector\Fixture;

class SkipUsedInNextStmt2
{
    public function run($resultLen, $result, $decimalSep)
    {
        foreach ($result as $i => $value) {
            ;
        }

        if ($i >= 0 && $result[$i] === $decimalSep) {
          $i--;
        }

        $result = substr($result, 0, $i + 1);
    }
}
