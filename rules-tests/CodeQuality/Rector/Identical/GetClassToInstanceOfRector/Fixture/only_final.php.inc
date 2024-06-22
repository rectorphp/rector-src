<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\GetClassToInstanceOfRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Identical\GetClassToInstanceOfRector\Source\SomeFinalClass;

final class OnlyFinal
{
    public function test(SomeFinalClass $other)
    {
        return get_class($other) !== SomeFinalClass::class;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\GetClassToInstanceOfRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Identical\GetClassToInstanceOfRector\Source\SomeFinalClass;

final class OnlyFinal
{
    public function test(SomeFinalClass $other)
    {
        return !$other instanceof \Rector\Tests\CodeQuality\Rector\Identical\GetClassToInstanceOfRector\Source\SomeFinalClass;
    }
}

?>
