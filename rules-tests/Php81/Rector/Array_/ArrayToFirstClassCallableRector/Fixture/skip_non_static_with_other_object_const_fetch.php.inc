<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeExternalObject;

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
