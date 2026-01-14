<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class ArrayValuesPattern
{
    public function run(array $array)
    {
        echo array_values($array)[0];
        echo array_values($array)[count($array) - 1];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class ArrayValuesPattern
{
    public function run(array $array)
    {
        echo array_first($array);
        echo array_last($array);
    }
}

?>
