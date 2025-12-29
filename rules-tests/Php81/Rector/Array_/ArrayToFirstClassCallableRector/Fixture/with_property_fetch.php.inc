<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeExternalObject;

final class WithPropertyFetch
{
    private SomeExternalObject $someExternalObject;

    public function __construct(SomeExternalObject $someExternalObject)
    {
        $this->someExternalObject = $someExternalObject;
    }

    public function run()
    {
        $name = [$this->someExternalObject, 'sleepOver'];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeExternalObject;

final class WithPropertyFetch
{
    private SomeExternalObject $someExternalObject;

    public function __construct(SomeExternalObject $someExternalObject)
    {
        $this->someExternalObject = $someExternalObject;
    }

    public function run()
    {
        $name = $this->someExternalObject->sleepOver(...);
    }
}

?>
