<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector\Fixture;

final class SkipIfStmtUsedInReturn
{
    public function run($otherValue)
    {
        $content = '';

        if ($otherValue === 1 && $otherValue === 2) {
            $content = execute($content);
        }

        return $content . 'execute';
    }
}
