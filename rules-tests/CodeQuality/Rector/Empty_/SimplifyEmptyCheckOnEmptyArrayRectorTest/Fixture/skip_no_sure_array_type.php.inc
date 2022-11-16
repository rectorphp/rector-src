<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class SkipNotSureArrayType
{
    public $arrayProperty;

    public function isEmpty($values): bool
    {
        return empty($values);
    }

    public function isEmptyMixedType(mixed $values): bool
    {
        return empty($values);
    }

    public function isEmptyArrayProperty(): bool
    {
        return empty($this->arrayProperty);
    }
}

?>
