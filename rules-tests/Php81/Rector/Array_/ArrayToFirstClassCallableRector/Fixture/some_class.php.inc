<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SomeClass
{
    public function run()
    {
        $name = [$this, 'name'];
    }

    public function name()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SomeClass
{
    public function run()
    {
        $name = $this->name(...);
    }

    public function name()
    {
    }
}

?>
