<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

class SkipFinalDocClassConstant
{
    /**
     * @final
     */
    public const BAR = 1;

    public function run()
    {
        static::BAR;
    }
}
