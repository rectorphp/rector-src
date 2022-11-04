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
        } elseif ($rand === 0) {
            switch ($rand) {
                case 1:
                    $result = "bar";
                    break;
                default:
                    $result = "bar default";
            }
        } else {
            switch ($rand) {
                case 1:
                    $result = "baz";
                    break;
                default:
                    $result = "baz default";
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
        } elseif ($rand === 0) {
            $result = match ($rand) {
                1 => "bar",
                default => "bar default",
            };
        } else {
            $result = match ($rand) {
                1 => "baz",
                default => "baz default",
            };
        }

        return $result;
    }
}

?>
