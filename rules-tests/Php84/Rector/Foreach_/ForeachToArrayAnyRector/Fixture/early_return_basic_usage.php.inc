<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

class EarlyReturnBasicUsage
{
    public function checkAnimal(array $animals)
    {
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                return true;
            }
        }
        return false;
    }

    public function checkNumber(array $numbers)
    {
        foreach ($numbers as $number) {
            if ($number > 10) {
                return true;
            }
        }
        return false;
    }

    public function checkWithKey(array $items)
    {
        foreach ($items as $key => $value) {
            if ($value === 'target') {
                return true;
            }
        }
        return false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

class EarlyReturnBasicUsage
{
    public function checkAnimal(array $animals)
    {
        return array_any($animals, fn($animal) => str_starts_with($animal, 'c'));
    }

    public function checkNumber(array $numbers)
    {
        return array_any($numbers, fn($number) => $number > 10);
    }

    public function checkWithKey(array $items)
    {
        return array_any($items, fn($value) => $value === 'target');
    }
}
?>
