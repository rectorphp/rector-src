<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class DefinedCase2
{
    private const A = true;
    private const B = false;

    public static function run($filter)
    {
        switch ($filter) {
            case self::A:
                $result = 'a';
                break;
            case self::B:
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

final class DefinedCase2
{
    private const A = true;
    private const B = false;

    public static function run($filter)
    {
        $result = match ($filter) {
            self::A => 'a',
            self::B => 'b',
            default => throw new \InvalidArgumentException(),
        };

        var_dump($result);
    }
}

?>
