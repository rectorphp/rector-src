<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorReturnComment
{
    public static function run(string $permission): bool
    {
        switch ($permission) {
            case 'VIEW':
                return false;
            case 'EDIT':
                return true;
        }

        // Comment
        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorReturnComment
{
    public static function run(string $permission): bool
    {
        return match ($permission) {
            'VIEW' => false,
            'EDIT' => true,
            // Comment
            default => true,
        };
    }
}

?>
