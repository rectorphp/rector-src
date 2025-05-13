<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class Fixture
{
    public function run(array $array)
    {
        echo $array[array_key_first($array)];
        echo $array[array_key_last($array)];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class Fixture
{
    public function run(array $array)
    {
        echo array_first($array);
        echo array_last($array);
    }
}

?>
