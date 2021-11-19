<?php

namespace Rector\Tests\DowngradePhp81\Rector\ClassMethod\DowngradeNewInInitializerRector\Fixture;

class SomeClass
{
    public function __construct(public Foo|stdClass $obj = new stdClass)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp81\Rector\ClassMethod\DowngradeNewInInitializerRector\Fixture;

class SomeClass
{
    public function __construct(public Foo|stdClass|null $obj = null)
    {
        $this->obj = $obj ?? new stdClass;
    }
}

?>
