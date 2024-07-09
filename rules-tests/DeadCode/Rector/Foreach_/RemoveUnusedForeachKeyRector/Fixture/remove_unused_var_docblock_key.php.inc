<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

class RemoveUnusedVarDocblockKey
{
    public function run()
    {
        $items = [];
        /**
         * @var int $key
         */
        foreach ($items as $key => $value) {
            $result = $value;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

class RemoveUnusedVarDocblockKey
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
