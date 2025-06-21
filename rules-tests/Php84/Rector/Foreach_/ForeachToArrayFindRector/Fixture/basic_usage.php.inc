<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindRector\Fixture;

class BasicUsage
{
    public function findAnimal($animals)
    {
        $found = null;
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = $animal;
                break;
            }
        }
        return $found;
    }

    public function findNumber($numbers)
    {
        $result = null;
        foreach ($numbers as $number) {
            if ($number > 10) {
                $result = $number;
                break;
            }
        }
        return $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindRector\Fixture;

class BasicUsage
{
    public function findAnimal($animals)
    {
        $found = array_find($animals, fn($animal) => str_starts_with($animal, 'c'));
        return $found;
    }

    public function findNumber($numbers)
    {
        $result = array_find($numbers, fn($number) => $number > 10);
        return $result;
    }
}

?>