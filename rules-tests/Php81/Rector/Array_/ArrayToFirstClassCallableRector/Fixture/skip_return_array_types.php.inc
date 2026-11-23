<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeExternalObject;

final class SkipReturnArrayTypes
{
    public function provideIterable(): iterable
    {
        return [SomeExternalObject::class, 'sleepStatic'];
    }

    public function provideNullable(): ?array
    {
        return [SomeExternalObject::class, 'sleepStatic'];
    }

    public function provideUnion(): array|string
    {
        return [SomeExternalObject::class, 'sleepStatic'];
    }

    public function provideClosures()
    {
        $closure = function (): array {
            return [SomeExternalObject::class, 'sleepStatic'];
        };

        $arrowFunction = fn (): array => [SomeExternalObject::class, 'sleepStatic'];
    }
}
