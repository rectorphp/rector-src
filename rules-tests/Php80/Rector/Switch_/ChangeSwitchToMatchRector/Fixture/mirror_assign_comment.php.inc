<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorAssignComment
{
    public static function run()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                // comment
                $response = 'get';
                break;
            default:
                $response = 'error';
                break;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorAssignComment
{
    public static function run()
    {
        $response = match ($_SERVER['REQUEST_METHOD']) {
            // comment
            'GET' => 'get',
            default => 'error',
        };
    }
}

?>
