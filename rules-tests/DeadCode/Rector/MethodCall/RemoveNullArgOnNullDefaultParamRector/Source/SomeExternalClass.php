<?php

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Source;

final class SomeExternalClass
{
    public function __construct(string $name, ?int $id = null)
    {
    }

    public function callWithDefaultNull(?string $name = null)
    {
    }

    public static function staticCallWithDefaultNull(?int $id = null)
    {
    }
}
