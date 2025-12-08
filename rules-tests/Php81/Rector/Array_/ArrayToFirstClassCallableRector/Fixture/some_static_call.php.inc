<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SomeStaticCall
{
    public function run()
    {
        $name = [self::class, 'name'];
    }

    public function name()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SomeStaticCall
{
    public function run()
    {
        $name = self::name(...);
    }

    public function name()
    {
    }
}

?>
