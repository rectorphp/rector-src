<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipParamHook
{
    public function __construct(private string $foo {
        get {
            return $this->foo;
        }
    })
    {
    }
}
