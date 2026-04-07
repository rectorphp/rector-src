<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

final class SkipOnStringableInterface implements \Stringable
{
    public function __toString(): string
    {
        return 'implements only';
    }
}
