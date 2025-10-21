<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlocksRector\Fixture;

class SkipUsedInNextStmt
{
    public function run($resultLen, $result, $decimalSep)
    {
        for ($i = $resultLen - 1; $i >= 0 && $result[$i] === '0'; $i--) {
            ;
        }

        if ($i >= 0 && $result[$i] === $decimalSep) {
          $i--;
        }

        $result = substr($result, 0, $i + 1);
    }
}
