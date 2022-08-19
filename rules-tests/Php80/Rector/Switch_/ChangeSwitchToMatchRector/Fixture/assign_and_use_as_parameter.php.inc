<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

use Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Source\SomeResponse;

final class AssignAndUseAsParameter
{
    public function getErrorResponse(int $errorCode): SomeResponse
    {
        switch ($errorCode) {
            case 1:
                $content = 'BAD REQUEST';
                break;
            case 2:
                $content = 'CHANGE STATUS FAILED';
                break;
            case 3:
                $content = 'NOT FOUND';
                break;
            default:
                $content = 'FAILED';
        }

        return new SomeResponse($content, 400);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

use Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Source\SomeResponse;

final class AssignAndUseAsParameter
{
    public function getErrorResponse(int $errorCode): SomeResponse
    {
        $content = match ($errorCode) {
            1 => 'BAD REQUEST',
            2 => 'CHANGE STATUS FAILED',
            3 => 'NOT FOUND',
            default => 'FAILED',
        };

        return new SomeResponse($content, 400);
    }
}

?>
