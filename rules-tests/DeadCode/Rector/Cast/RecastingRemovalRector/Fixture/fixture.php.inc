<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

class Fixture
{
    public function run()
    {
        $string = '';
        $string = (string) $string;

        $array = [];
        $array = (array) $array;

        $array = (array) $string;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

class Fixture
{
    public function run()
    {
        $string = '';
        $string = $string;

        $array = [];
        $array = $array;

        $array = (array) $string;
    }
}

?>
