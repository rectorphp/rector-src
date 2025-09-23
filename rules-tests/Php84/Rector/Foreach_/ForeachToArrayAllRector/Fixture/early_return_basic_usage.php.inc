<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class EarlyReturnBasicUsage
{
    public function checkAllAnimalsStartWithC(array $animals)
    {
        foreach ($animals as $animal) {
            if (!str_starts_with($animal, 'c')) {
                return false;
            }
        }
        return true;
    }

    public function checkAllNumbersGreaterThanTen(array $numbers)
    {
        foreach ($numbers as $number) {
            if (!($number > 10)) {
                return false;
            }
        }
        return true;
    }

    public function checkAllItemsEqualTarget(array $items)
    {
        foreach ($items as $key => $value) {
            if (!($value === 'target')) {
                return false;
            }
        }
        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class EarlyReturnBasicUsage
{
    public function checkAllAnimalsStartWithC(array $animals)
    {
        return array_all($animals, fn($animal) => str_starts_with($animal, 'c'));
    }

    public function checkAllNumbersGreaterThanTen(array $numbers)
    {
        return array_all($numbers, fn($number) => $number > 10);
    }

    public function checkAllItemsEqualTarget(array $items)
    {
        return array_all($items, fn($value) => $value === 'target');
    }
}

?>
