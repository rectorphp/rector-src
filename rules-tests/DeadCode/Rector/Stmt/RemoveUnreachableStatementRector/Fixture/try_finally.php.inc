<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

// @see https://3v4l.org/W54bM
class TryFinally
{
    public function setMultiple($values, $ttl = null): bool
    {
        try {
            throw new Exception();
        } finally {
            echo 3;
        }

        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

// @see https://3v4l.org/W54bM
class TryFinally
{
    public function setMultiple($values, $ttl = null): bool
    {
        try {
            throw new Exception();
        } finally {
            echo 3;
        }
    }
}

?>
