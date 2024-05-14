<?php

final class SkipPossibleArray2
{
    public function run($key): void
    {
        if (\is_array($key)) {
            $dayKey = [$key[0], $key[1] . '_day'];
        } elseif (rand(0, 1)) {
            $dayKey = $key . '_day';
        } else {
            $dayKey = null;
        }
    }
}
