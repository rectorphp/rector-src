<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindRector\Fixture;

class WithKey
{
    public function findAnimal(array $animals)
    {
        $found = null;
        foreach ($animals as $key => $animal) {
            if (str_starts_with($animal, 'c') && $key === 1) {
                $found = $animal;
                break;
            }
        }
        return $found;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindRector\Fixture;

class WithKey
{
    public function findAnimal(array $animals)
    {
        $found = array_find($animals, fn($animal, $key) => str_starts_with($animal, 'c') && $key === 1);
        return $found;
    }
}

?>