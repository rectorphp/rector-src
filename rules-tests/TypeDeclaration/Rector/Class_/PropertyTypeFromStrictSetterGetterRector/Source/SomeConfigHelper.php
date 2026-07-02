<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Source;

final class SomeConfigHelper
{
    public function get(string $key, string $default): string
    {
        return $default;
    }
}
