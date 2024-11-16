<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

final class SkipOnInterface implements \Stringable
{
    public function __toString(): string
    {
        return 'implements only';
    }
}
