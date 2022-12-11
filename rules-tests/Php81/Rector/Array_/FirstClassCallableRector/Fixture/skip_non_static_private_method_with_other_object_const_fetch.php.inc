<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Source\SomeExternalObject;

final class SkipNonStaticPrivateMethodWithOtherObjectConstFetch
{
    public function provideListenerPriority(): array
    {
         return [
             'listener' => [
                 [SomeExternalObject::class, 'sleepPrivateStatic'],
             ],
         ];
    }
}
