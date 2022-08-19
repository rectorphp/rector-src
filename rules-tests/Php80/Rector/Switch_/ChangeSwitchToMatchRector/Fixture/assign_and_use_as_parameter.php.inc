<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class AssignAndUseAsParameter
{
    public function getErrorResponse(int $errorCode): Response
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

        return new Response($content, 400);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class AssignAndUseAsParameter
{
    public function getErrorResponse(int $errorCode): Response
    {
        $content = match ($errorCode) {
            1 => 'BAD REQUEST',
            2 => 'CHANGE STATUS FAILED',
            3 => 'NOT FOUND',
            default => 'FAILED',
        };

        return new Response($content, 400);
    }
}

?>

