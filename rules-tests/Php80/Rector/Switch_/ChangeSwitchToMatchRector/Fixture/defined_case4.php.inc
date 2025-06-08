<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class FromVariable
{
    public static function run($filter, bool $variable = true, bool $variable2 = false)
    {
        switch ($filter) {
            case $variable:
                $result = 'a';
                break;
            case $variable2:
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

final class FromVariable
{
    public static function run($filter, bool $variable = true, bool $variable2 = false)
    {
        $result = match ($filter) {
            $variable => 'a',
            $variable2 => 'b',
            default => throw new \InvalidArgumentException(),
        };

        var_dump($result);
    }
}

?>
