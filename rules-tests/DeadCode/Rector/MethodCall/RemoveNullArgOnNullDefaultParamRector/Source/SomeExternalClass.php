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

    public static function withMiddleNotNullDefault(?int $id = null, int $data = 1, ?string $name = null, ?string $item = null)
    {
    }

    public static function withRequiredArgument(?int $id)
    {
    }
}
