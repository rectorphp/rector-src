<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Fixture;

final class OnFinalClass
{
    protected const BAR = 1;

    public const BAZ = 1;

    public function run(): void
    {
        echo static::BAR;
        echo static::BAZ;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Fixture;

final class OnFinalClass
{
    protected const BAR = 1;

    public const BAZ = 1;

    public function run(): void
    {
        echo self::BAR;
        echo self::BAZ;
    }
}

?>
