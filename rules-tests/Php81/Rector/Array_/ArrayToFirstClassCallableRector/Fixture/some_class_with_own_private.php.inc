<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

final class SomeClassWithOwnPrivate
{
    public function run()
    {
        $name = [$this, 'name'];
    }

    private function name()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

final class SomeClassWithOwnPrivate
{
    public function run()
    {
        $name = $this->name(...);
    }

    private function name()
    {
    }
}

?>
