<?php

namespace Rector\Tests\DowngradePhp80\Rector\Expression\DowngradeMatchToSwitchRector\Fixture;

<<<<<<< HEAD:rules-tests/DowngradePhp80/Rector/Expression/DowngradeMatchToSwitchRector/Fixture/inside_array.php.inc
final class InsideArray
=======
final class InsideArrayItem
>>>>>>> extract use case with missing scope on new array:rules-tests/DowngradePhp80/Rector/Expression/DowngradeMatchToSwitchRector/Fixture/inside_array_item.php.inc
{
    public function run($statusCode)
    {
        return [
            match ($statusCode) {
                200, 300 => null,
                400 => 'not found',
                default => 'unknown status code',
            },
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\Expression\DowngradeMatchToSwitchRector\Fixture;

<<<<<<< HEAD:rules-tests/DowngradePhp80/Rector/Expression/DowngradeMatchToSwitchRector/Fixture/inside_array.php.inc
final class InsideArray
=======
final class InsideArrayItem
>>>>>>> extract use case with missing scope on new array:rules-tests/DowngradePhp80/Rector/Expression/DowngradeMatchToSwitchRector/Fixture/inside_array_item.php.inc
{
    public function run($statusCode)
    {
        return [
            (function () use ($statusCode) {
                switch ($statusCode) {
                    case 200:
                    case 300:
                        return null;
                    case 400:
                        return 'not found';
                    default:
                        return 'unknown status code';
                }
            })(),
        ];
    }
}

?>
