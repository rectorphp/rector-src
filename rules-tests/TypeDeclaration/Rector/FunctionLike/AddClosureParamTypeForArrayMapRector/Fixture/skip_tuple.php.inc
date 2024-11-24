<?php

class SkipTuple
{
    /**
     * @param array{0: list<string>, 1: list<string>} $tuple
     */
    public function run(array $tuple)
    {
        array_map(function ($first, $second) {
            return $first . $second;
        }, ...$tuple);
    }
}
