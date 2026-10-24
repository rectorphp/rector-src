<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

final class WithoutStaticAccess
{
    public function run(string $value): string
    {
        return strtoupper($value);
    }
}
