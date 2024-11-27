<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorReturnCommentWithDefault
{
    public static function run(string $permission): bool
    {
        switch ($permission) {
            case 'VIEW':
                // Comment 0
                return false;
            case 'EDIT':
                // Comment 1
                return true;
            default:
                // Comment 2
                return true;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorReturnCommentWithDefault
{
    public static function run(string $permission): bool
    {
        return match ($permission) {
            // Comment 0
            'VIEW' => false,
            // Comment 1
            'EDIT' => true,
            // Comment 2
            default => true,
        };
    }
}

?>
