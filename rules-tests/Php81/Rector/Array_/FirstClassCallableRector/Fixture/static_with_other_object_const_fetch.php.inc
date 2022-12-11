<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Source\SomeExternalObject;

final class StaticWithOtherObjectConstFetch
{
    public function provideListenerPriority(): array
    {
         return [SomeExternalObject::class, 'sleepStatic'];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Source\SomeExternalObject;

final class StaticWithOtherObjectConstFetch
{
    public function provideListenerPriority(): array
    {
         return SomeExternalObject::sleepStatic(...);
    }
}

?>
