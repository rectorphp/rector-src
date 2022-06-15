<?php

class Preg
{
    /**
     * @param array<string, callable> $pattern
     * @param string $subject
     * @param int    $count Set by method
     * @param int    $flags PREG_OFFSET_CAPTURE is supported, PREG_UNMATCHED_AS_NULL is always set
     */
    public static function replaceCallbackArray(array $pattern, $subject, int $limit = -1, int &$count = null, int $flags = 0): string
    {
        if (!is_scalar($subject)) {
            if (is_array($subject)) {
                throw new \InvalidArgumentException(static::ARRAY_MSG);
            }

            throw new \TypeError(sprintf(static::INVALID_TYPE_MSG, gettype($subject)));
        }
    }
}
