<?php

namespace Rector\Tests\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector\Fixture;

final class BigFloats
{
    public function run()
    {
        $float = 1000.0;
        $float2 = 1000000.0;
        $float3 = 1000500.001;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector\Fixture;

final class BigFloats
{
    public function run()
    {
        $float = 1000.0;
        $float2 = 1_000_000.0;
        $float3 = 1_000_500.001;
    }
}

?>
