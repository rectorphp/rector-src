<?php

// "$value{0}" cannot be parsed by PHPStan, so the class is unknown to reflection and must be skipped, not crash
class SkipClassUnknownToReflection
{
    function SkipClassUnknownToReflection()
    {
    }

    function first($value)
    {
        return $value{0};
    }
}
