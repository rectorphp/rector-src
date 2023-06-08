<?php

namespace Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Fixture;

final class PrivateConstantWithStatic
{
    private const BAR = 1;

    public function baz(): void
    {
        echo static::BAR;
    }
}

?>
-----
<?php

namespace Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Fixture;

final class PrivateConstantWithStatic
{
    private const BAR = 1;

    public function baz(): void
    {
        echo self::BAR;
    }
}

?>
