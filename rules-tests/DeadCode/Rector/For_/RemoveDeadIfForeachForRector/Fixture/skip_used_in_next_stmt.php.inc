<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadIfForeachForRector\Fixture;

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
