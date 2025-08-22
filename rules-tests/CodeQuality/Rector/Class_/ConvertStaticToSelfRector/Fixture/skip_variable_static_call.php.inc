<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

final class SkipFromVariable
{
    private const BAR = 1;

    private static string $privateProperty = 'test';

    private static function privateMethod(): void
    {
    }

    public function execute(string $static): void
    {
        $static::$privateProperty;
        $static::privateMethod();
        $static::BAR;
    }
}
