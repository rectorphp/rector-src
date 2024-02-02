<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector\Fixture;

final class SkipAppendVariableValueInIf
{
    function run(): string
    {
        $var = 'a';
        if (rand(0, 1)) {
            $var = 'b' ;
        }
        if (rand(0, 2)) {
            $var .= 'c' ;
        }
        return $var;
    }
}

?>
