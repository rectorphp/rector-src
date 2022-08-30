<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

class Fixture
{
    public function run()
    {
        $items = [];
        foreach ($items as $key => $value) {
            $result = $value;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

class Fixture
{
    public function run()
    {
        $items = [];
        foreach ($items as $value) {
            $result = $value;
        }
    }
}

?>
