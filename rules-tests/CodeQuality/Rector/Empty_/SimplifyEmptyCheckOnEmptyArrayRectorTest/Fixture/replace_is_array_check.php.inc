<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class ReplaceIsArrayCheck
{
    public $arrayProperty = [];

    public function isEmptyArray($values): bool
    {
        return is_array($values) && empty($values);
    }

    public function isNotEmptyArray($values): bool
    {
        return is_array($values) && !empty($values);
    }

    public function isEmptyArrayProperty(): bool
    {
        return is_array($this->arrayProperty) && empty($this->arrayProperty);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class ReplaceIsArrayCheck
{
    public $arrayProperty = [];

    public function isEmptyArray($values): bool
    {
        return is_array($values) && $values === [];
    }

    public function isNotEmptyArray($values): bool
    {
        return is_array($values) && !($values === []);
    }

    public function isEmptyArrayProperty(): bool
    {
        return is_array($this->arrayProperty) && $this->arrayProperty === [];
    }
}

?>
