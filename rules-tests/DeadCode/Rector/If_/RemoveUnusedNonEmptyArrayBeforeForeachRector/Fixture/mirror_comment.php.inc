<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class MirrorComment
{
    public function run(array $values)
    {
        // a comment
        if ($values !== []) {
            foreach ($values as $value) {
                echo $value;
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class MirrorComment
{
    public function run(array $values)
    {
        // a comment
        foreach ($values as $value) {
            echo $value;
        }
    }
}

?>
