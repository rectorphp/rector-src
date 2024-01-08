<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ChangeSwitchTernary\Fixture;

final class Fixture
{
    public function run()
    {
        $random = rand(1, 3);

        if(rand(0, 1)) {
            // this if makes it crash
        } else {
            switch($random) {
                case 1:
                    $out = 'one';
                break;
                case 2:
                    $out = 'two';
                break;
                default:
                    $out = 'other';
            }
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ChangeSwitchTernary\Fixture;

final class Fixture
{
    public function run()
    {
        $random = rand(1, 3);

        if(rand(0, 1)) {
            // this if makes it crash
        } else {
            $out = match ($random) {
                1 => 'one',
                2 => 'two',
                default => 'other',
            };
        }
    }
}

?>
