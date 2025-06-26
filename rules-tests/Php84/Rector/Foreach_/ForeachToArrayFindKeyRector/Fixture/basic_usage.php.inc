<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector\Fixture;

class BasicUsage
{
    public function findAnimalKey(array $animals)
    {
        $found = null;
        foreach ($animals as $idx => $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = $idx;
                break;
            }
        }
        return $found;
    }

    public function findNumberKey(array $numbers)
    {
        $result = null;
        foreach ($numbers as $key => $number) {
            if ($number > 10) {
                $result = $key;
                break;
            }
        }
        return $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector\Fixture;

class BasicUsage
{
    public function findAnimalKey(array $animals)
    {
        $found = array_find_key($animals, fn($animal) => str_starts_with($animal, 'c'));
        return $found;
    }

    public function findNumberKey(array $numbers)
    {
        $result = array_find_key($numbers, fn($number) => $number > 10);
        return $result;
    }
}

?>