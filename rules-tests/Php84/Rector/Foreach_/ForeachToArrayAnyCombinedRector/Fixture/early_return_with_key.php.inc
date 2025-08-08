<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyCombinedRector\Fixture;

class WithKey
{
    public function checkAnimal(array $animals)
    {
        foreach ($animals as $key => $animal) {
            if (str_starts_with($animal, 'c') && $key === 1) {
                return true;
            }
        }
        return false;
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
        return array_any($animals, fn($animal, $key) => str_starts_with($animal, 'c') && $key === 1);
    }
}

?>
