<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector\Fixture;

class SkipAlreadySimplified
{
    public function run()
    {
        $a = 5;
        $b = 10;
        $result = $a <= 20 && $b > 50;

        $c = 1000;
        $d = $a !== null && $b >= $c;
        $d = $a !== null || $b >= $c;

        $e = $a && $b === $c;
        $f = $a <= $b && $c > $d;
    }
}
