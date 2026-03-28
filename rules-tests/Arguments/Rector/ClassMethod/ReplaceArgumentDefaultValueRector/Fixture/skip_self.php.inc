<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

class SomeBase
{
    public const PATH = 'path';

    public static function test1(string $path)
    {
        \Mage::getStoreConfig(self::PATH);
    }
}