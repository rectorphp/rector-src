<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source;

final class SomeClass
{
    public function getValue(): string
    {
        return 'value';
    }
    public function getKey(): string
    {
        return 'key';
    }
}
