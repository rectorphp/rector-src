<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

class SkipFinalDocProperty
{
    /**
     * @final
     */
    public static string $publicProperty = 'test';

    public function run()
    {
        static::$publicProperty;
    }
}

?>
