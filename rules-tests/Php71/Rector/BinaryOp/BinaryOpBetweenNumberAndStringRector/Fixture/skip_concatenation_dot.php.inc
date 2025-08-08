<?php

namespace Rector\Tests\Php71\Rector\BinaryOp\BinaryOpBetweenNumberAndStringRector\Fixture;

final class SkipConcatenationDot
{
    public function run()
    {
        $string = 'this is a string to concatenate with the number';
        $number = 10;
        $value = $string . ' ' . $number;
    }
}
