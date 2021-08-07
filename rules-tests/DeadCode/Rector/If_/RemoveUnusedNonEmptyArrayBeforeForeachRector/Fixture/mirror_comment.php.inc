<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class MirrorComment
{
    public function run(array $values)
    {
        // a comment
        // another
        // another again
        if ($values !== []) {
            // another comment deep
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
        // another
        // another again
        // another comment deep
        foreach ($values as $value) {
            echo $value;
        }
    }
}

?>
