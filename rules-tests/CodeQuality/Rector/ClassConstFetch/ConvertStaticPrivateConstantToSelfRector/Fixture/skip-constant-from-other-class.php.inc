<?php

namespace Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Fixture;

final class SkipConstantFromOtherClass
{
    public function run(): void
    {
        echo \DateTimeInterface::ATOM;
    }
}
