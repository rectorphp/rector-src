<?php

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullNamedArgOnNullDefaultParamRector\Source;

final class SomeExternalClass
{
    public function __construct(string $name, ?int $id = null)
    {
    }

    public static function withMiddleNotNullDefault(?int $id = null, int $data = 1, ?string $name = null, ?string $item = null)
    {
    }
}
