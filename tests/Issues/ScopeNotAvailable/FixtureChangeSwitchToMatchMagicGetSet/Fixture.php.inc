<?php

namespace Rector\Core\Tests\Issues\FixtureChangeSwitchToMatchMagicGetSet\Fixture;

final class Fixture
{
    public function run()
    {
        $rand = random_int(0, 1);
        if ($rand) {
            switch ($rand) {
                case 1:
                    $result = "foo";
                    break;
                default:
                    $result = "foo default";
            }
        } else {
            switch ($rand) {
                case 1:
                    $result = "bar";
                    break;
                default:
                    $result = "bar default";
            }
        }

        return $result;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\FixtureChangeSwitchToMatchMagicGetSet\Fixture;

final class Fixture
{
    public function run()
    {
        $rand = random_int(0, 1);
        if ($rand) {
            $result = match ($rand) {
                1 => "foo",
                default => "foo default",
            };
        } else {
            $result = match ($rand) {
                1 => "bar",
                default => "bar default",
            };
        }

        return $result;
    }
}

?>
