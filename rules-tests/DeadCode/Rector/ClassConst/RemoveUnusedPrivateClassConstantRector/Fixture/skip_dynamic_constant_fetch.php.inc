<?php declare(strict_types=1);

final class SkipDynamicConstantFetch
{
    private const string SOME_CONST = '1';

    public function hello($constName): string
    {
        return $this::{$constName};
    }
}
