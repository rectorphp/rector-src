<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorCommentWithDefaultExceptionWithNextStmt
{
    public function run($value)
    {
        switch ($value) {
            case 100:
                // comment 0
                $statement = 100;
                break;

            default:
                // comment 1
                throw new \InvalidArgumentException();
        }

        // comment 2
        return $statement;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorCommentWithDefaultExceptionWithNextStmt
{
    public function run($value)
    {
        $statement = match ($value) {
            // comment 0
            100 => 100,
            // comment 1
            default => throw new \InvalidArgumentException(),
        };

        // comment 2
        return $statement;
    }
}

?>
