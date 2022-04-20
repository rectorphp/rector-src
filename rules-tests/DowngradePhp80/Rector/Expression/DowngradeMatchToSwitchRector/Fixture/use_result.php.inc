<?php

namespace Rector\Tests\DowngradePhp80\Rector\Expression\DowngradeMatchToSwitchRector\Fixture;

class UseResult
{
    public function run($statusCode)
    {
        echo match ($statusCode) {
            200, 300 => null,
            400 => 'not found',
            default => 'unknown status code',
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\Expression\DowngradeMatchToSwitchRector\Fixture;

class UseResult
{
    public function run($statusCode)
    {
        switch ($statusCode) {
            case 200:
            case 300:
                echo null;
            case 400:
                echo 'not found';
            default:
                echo 'unknown status code';
        }
    }
}

?>
