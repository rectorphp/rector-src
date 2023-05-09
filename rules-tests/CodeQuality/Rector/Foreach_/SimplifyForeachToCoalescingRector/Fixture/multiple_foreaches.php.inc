<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector\Fixture;

final class MultipleForeaches
{
    public function run()
    {
        $newValue = null;
        $nextValue = null;

        $values = [];
        $input = '123';

        foreach ($values as $key => $value) {
            if ($key === $input) {
                $newValue = $value;
            }
        }

        foreach ($values as $key => $value) {
            if ($input === $key) {
                $nextValue = $value;
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector\Fixture;

final class MultipleForeaches
{
    public function run()
    {
        $newValue = null;
        $nextValue = null;

        $values = [];
        $input = '123';

        $newValue = $values[$input] ?? $newValue;

        $nextValue = $values[$input] ?? $nextValue;
    }
}

?>
