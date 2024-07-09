<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

class RemoveUnusedVarDocblockKey
{
    public function run()
    {
        $items = [];
        /**
         * @var int $key
         * @var string $value
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
        /**
         * @var string $value
         */
        foreach ($items as $value) {
            $result = $value;
        }
    }
}

?>
