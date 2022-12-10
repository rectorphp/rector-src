<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Source\SomeExternalObject;

final class SkipNonStaticWithOtherObjectConstFetch
{
    public function provideListenerPriority(): array
    {
         return [
             'listener' => [
                 [SomeExternalObject::class, 'sleepOver'],
             ],
         ];
    }
}
