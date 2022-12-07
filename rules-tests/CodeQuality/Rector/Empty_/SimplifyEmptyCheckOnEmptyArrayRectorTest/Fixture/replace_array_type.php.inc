<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class ReplaceArrayType
{
    public array $arrayProperty = [];

    public function isEmptyArray(array $values): bool
    {
        return empty($values);
    }

    public function isNotEmptyArray(array $values): bool
    {
        return !empty($values);
    }

    public function isEmptyArrayMixedType(mixed $values): bool
    {
        if (!is_array($values)) {
            return false;
        }

        return empty($values);
    }

    public function doStuffContainingArrayValues(): bool
    {
        $values = [];

        return empty($values);
    }

    public function isEmptyArrayProperty(): bool
    {
        return empty($this->arrayProperty);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class ReplaceArrayType
{
    public array $arrayProperty = [];

    public function isEmptyArray(array $values): bool
    {
        return $values === [];
    }

    public function isNotEmptyArray(array $values): bool
    {
        return $values !== [];
    }

    public function isEmptyArrayMixedType(mixed $values): bool
    {
        if (!is_array($values)) {
            return false;
        }

        return $values === [];
    }

    public function doStuffContainingArrayValues(): bool
    {
        $values = [];

        return $values === [];
    }

    public function isEmptyArrayProperty(): bool
    {
        return $this->arrayProperty === [];
    }
}

?>
