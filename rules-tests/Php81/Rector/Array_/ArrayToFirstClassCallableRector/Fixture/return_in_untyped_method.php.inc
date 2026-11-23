<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeExternalObject;

final class ReturnInUntypedMethod
{
    public function provideListener()
    {
        return [SomeExternalObject::class, 'sleepStatic'];
    }

    public function provideNestedClosure(): array
    {
        return [
            'listener' => function () {
                return [SomeExternalObject::class, 'sleepStatic'];
            },
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeExternalObject;

final class ReturnInUntypedMethod
{
    public function provideListener()
    {
        return SomeExternalObject::sleepStatic(...);
    }

    public function provideNestedClosure(): array
    {
        return [
            'listener' => function () {
                return SomeExternalObject::sleepStatic(...);
            },
        ];
    }
}

?>
