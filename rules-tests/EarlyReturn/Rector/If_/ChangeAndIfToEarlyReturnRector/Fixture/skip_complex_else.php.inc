<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector\Fixture;

class SkipComplexElse
{
    public function run($sql)
    {
        if (!empty($sql) && $sql=== []) {
            return true;
        }

        return ['very' => $sql, 'complex', 'expression', 'to_duplicate'];
    }
}
