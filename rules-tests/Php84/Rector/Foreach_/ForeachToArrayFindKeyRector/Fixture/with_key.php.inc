<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector\Fixture;

class WithKey
{
    public function findAnimalKey(array $animals)
    {
        $found = null;
        foreach ($animals as $idx => $animal) {
            if (str_starts_with($animal, 'c') && $idx === 1) {
                $found = $idx;
                break;
            }
        }
        return $found;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector\Fixture;

class WithKey
{
    public function findAnimalKey(array $animals)
    {
        $found = array_find_key($animals, fn($animal, $idx) => str_starts_with($animal, 'c') && $idx === 1);
        return $found;
    }
}

?>