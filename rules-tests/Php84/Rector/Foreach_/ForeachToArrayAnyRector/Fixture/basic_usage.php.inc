<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

class BasicUsage
{
    public function checkAnimal(array $animals)
    {
        $found = false;
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = true;
                break;
            }
        }
        return $found;
    }

    public function checkNumber(array $numbers)
    {
        $exists = false;
        foreach ($numbers as $number) {
            if ($number > 10) {
                $exists = true;
                break;
            }
        }
        return $exists;
    }

    public function checkWithKey(array $items)
    {
        $hasMatch = false;
        foreach ($items as $key => $value) {
            if ($value === 'target') {
                $hasMatch = true;
                break;
            }
        }
        return $hasMatch;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

class BasicUsage
{
    public function checkAnimal(array $animals)
    {
        $found = array_any($animals, fn($animal) => str_starts_with($animal, 'c'));
        return $found;
    }

    public function checkNumber(array $numbers)
    {
        $exists = array_any($numbers, fn($number) => $number > 10);
        return $exists;
    }

    public function checkWithKey(array $items)
    {
        $hasMatch = array_any($items, fn($value) => $value === 'target');
        return $hasMatch;
    }
}

?>
