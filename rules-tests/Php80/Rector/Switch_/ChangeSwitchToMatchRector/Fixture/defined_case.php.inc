<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class DefinedCase
{
    public static function run($filter)
    {
        $a = true;
        $b = false;

        switch ($filter) {
            case $a:
                $result = 'a';
                break;
            case $b:
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

final class DefinedCase
{
    public static function run($filter)
    {
        $a = true;
        $b = false;

        $result = match ($filter) {
            $a => 'a',
            $b => 'b',
            default => throw new \InvalidArgumentException(),
        };

        var_dump($result);
    }
}

?>
