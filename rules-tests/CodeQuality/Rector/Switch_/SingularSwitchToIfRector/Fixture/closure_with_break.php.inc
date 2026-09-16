<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SingularSwitchToIfRector\Fixture;

final class ClosureWithBreak
{
    public function run($value, array $items)
    {
        switch ($value) {
            case 100:
                $callback = function () use ($items) {
                    foreach ($items as $item) {
                        break;
                    }
                };
                break;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SingularSwitchToIfRector\Fixture;

final class ClosureWithBreak
{
    public function run($value, array $items)
    {
        if ($value === 100) {
            $callback = function () use ($items) {
                foreach ($items as $item) {
                    break;
                }
            };
        }
    }
}

?>
