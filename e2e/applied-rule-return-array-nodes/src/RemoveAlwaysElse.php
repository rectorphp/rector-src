<?php declare(strict_types=1);

class RemoveAlwaysElse
{
    public function run($value)
    {
        if ($value) {
            throw new \InvalidStateException;
        } else {
            return 10;
        }
    }
}
