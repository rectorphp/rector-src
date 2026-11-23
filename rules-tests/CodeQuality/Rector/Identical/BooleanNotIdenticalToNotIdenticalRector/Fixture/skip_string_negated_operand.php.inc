<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector\Fixture;

class SkipStringNegatedOperand
{
    public function run()
    {
        $a = 'true';
        $b = false;

        var_dump(! $a === $b); // true
        var_dump(! ($a === $b)); // true
        var_dump($a !== $b); // true
    }
}

?>
