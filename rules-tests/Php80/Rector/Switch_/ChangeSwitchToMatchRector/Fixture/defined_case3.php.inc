<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class DefinedCase3
{
    private const A = true;
    private const B = false;

    public static function run($filter)
    {
        switch ($filter) {
            case DefinedCase3::A:
                $result = 'a';
                break;
            case DefinedCase3::B:
                $result = 'b';
                break;
            default:
                throw new \InvalidArgumentException();
        }

        var_dump($result);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class DefinedCase3
{
    private const A = true;
    private const B = false;

    public static function run($filter)
    {
        $result = match ($filter) {
            DefinedCase3::A => 'a',
            DefinedCase3::B => 'b',
            default => throw new \InvalidArgumentException(),
        };

        var_dump($result);
    }
}

?>
