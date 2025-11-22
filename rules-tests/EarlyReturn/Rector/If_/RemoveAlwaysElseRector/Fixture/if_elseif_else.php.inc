<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

final class IfElseIfElse
{
    public function run($value, $formatForEmail, $time_context, $direction)
    {
        if ($value == 0) {
            return $value;
        } elseif ($formatForEmail) {
            $value .= '|' . 1;

            return $time_context . '&nbsp;&nbsp;//&nbsp;&nbsp;' . $direction . $value . ' minute(s)';
        } else {
            return $value;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

final class IfElseIfElse
{
    public function run($value, $formatForEmail, $time_context, $direction)
    {
        if ($value == 0) {
            return $value;
        }
        if ($formatForEmail) {
            $value .= '|' . 1;
            return $time_context . '&nbsp;&nbsp;//&nbsp;&nbsp;' . $direction . $value . ' minute(s)';
        }
        return $value;
    }
}

?>
