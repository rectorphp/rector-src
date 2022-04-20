<?php

namespace Rector\Tests\DowngradePhp80\Rector\Expression\DowngradeMatchToSwitchRector\Fixture;

class UnusedResult
{
    public function run($statusCode)
    {
        match ($statusCode) {
            200, 300 => null,
            400 => (function() { echo 'not found'; })(),
            default => 'unknown status code',
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\Expression\DowngradeMatchToSwitchRector\Fixture;

class UnusedResult
{
    public function run($statusCode)
    {
        switch ($statusCode) {
            case 200:
            case 300:
                null;
            case 400:
                (function() { echo 'not found'; })(),
            default:
                'unknown status code';
        }
    }
}

?>
