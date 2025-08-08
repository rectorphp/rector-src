<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyCombinedRector\Fixture;

class WithKey
{
    public function checkAnimal(array $animals)
    {
        $found = false;
        foreach ($animals as $key => $animal) {
            if (str_starts_with($animal, 'c') && $key === 1) {
                $found = true;
                break;
            }
        }
        return $found;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyCombinedRector\Fixture;

class WithKey
{
    public function checkAnimal(array $animals)
    {
        $found = array_any($animals, fn($animal, $key) => str_starts_with($animal, 'c') && $key === 1);
        return $found;
    }
}

?>
