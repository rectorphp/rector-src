<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorAssignComment
{
    public static function run(string $method)
    {
        switch ($method) {
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
    public static function run(string $method)
    {
        $response = match ($method) {
            // comment
            'GET' => 'get',
            default => 'error',
        };
    }
}

?>
