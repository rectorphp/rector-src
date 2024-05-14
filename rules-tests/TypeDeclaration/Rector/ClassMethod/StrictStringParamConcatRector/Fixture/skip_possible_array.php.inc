<?php

final class SkipPossibleArray
{
    public function run($key): void
    {
        if (\is_array($key)) {
            $dayKey = [$key[0], $key[1] . '_day'];
        } else {
            $dayKey = $key . '_day';
        }
    }
}
