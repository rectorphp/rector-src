<?php

final class SkipDifferentNamespacedFunction
{
    public function run(): void
    {
        $addressParts = Psl\Str\split($addressLine, $houseNumber);
    }
}