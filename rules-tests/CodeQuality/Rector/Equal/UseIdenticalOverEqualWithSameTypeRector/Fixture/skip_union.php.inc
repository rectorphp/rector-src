<?php

final class SkipUnion
{
    public function run(int|string|float $firstValue, int|string|float $secondValue)
    {
        return $firstValue == $secondValue;
    }
}
