<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeExternalObject;

final class ReturnArrayCallableUnion
{
    public function provideListener(): array|callable
    {
        return [SomeExternalObject::class, 'sleepStatic'];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeExternalObject;

final class ReturnArrayCallableUnion
{
    public function provideListener(): array|callable
    {
        return SomeExternalObject::sleepStatic(...);
    }
}

?>
