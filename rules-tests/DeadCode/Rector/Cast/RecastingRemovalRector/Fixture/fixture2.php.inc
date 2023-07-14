<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

class Fixture2
{
    public function run()
    {
        $object = new \stdClass();
        $object = (object) $object;
        $object = (array) $object;

        $int = 5;
        $float = (float) $int;
        $int = (int) $int;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

class Fixture2
{
    public function run()
    {
        $object = new \stdClass();
        $object = $object;
        $object = (array) $object;

        $int = 5;
        $float = (float) $int;
        $int = $int;
    }
}

?>
