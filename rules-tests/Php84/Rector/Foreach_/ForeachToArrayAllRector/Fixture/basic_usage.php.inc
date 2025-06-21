<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class BasicUsage
{
    public function checkAllAnimalsStartWithC($animals)
    {
        $found = true;
        foreach ($animals as $animal) {
            if (!str_starts_with($animal, 'c')) {
                $found = false;
                break;
            }
        }
        return $found;
    }

    public function checkAllNumbersGreaterThanTen($numbers)
    {
        $allGreater = true;
        foreach ($numbers as $number) {
            if (!($number > 10)) {
                $allGreater = false;
                break;
            }
        }
        return $allGreater;
    }

    public function checkAllItemsEqualTarget($items)
    {
        $allMatch = true;
        foreach ($items as $key => $value) {
            if (!($value === 'target')) {
                $allMatch = false;
                break;
            }
        }
        return $allMatch;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class BasicUsage
{
    public function checkAllAnimalsStartWithC($animals)
    {
        $found = array_all($animals, fn($animal) => str_starts_with($animal, 'c'));
        return $found;
    }

    public function checkAllNumbersGreaterThanTen($numbers)
    {
        $allGreater = array_all($numbers, fn($number) => $number > 10);
        return $allGreater;
    }

    public function checkAllItemsEqualTarget($items)
    {
        $allMatch = array_all($items, fn($value) => $value === 'target');
        return $allMatch;
    }
}

?>