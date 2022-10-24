<?php

namespace Rector\Core\Tests\Issues\IssueRemoveAlwaysElseReturnEarlyIfVariable\Fixture;

final class Fixture
{
    public function get($a, $b = false)
    {
        if ($b !== true) {
            $value = $a * 5;

            if ($value === 'hello') {
                $value = false;
            }

            return $value;
        } elseif ($a === true) {
            return false;
        }

        return true;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IssueRemoveAlwaysElseReturnEarlyIfVariable\Fixture;

final class Fixture
{
    public function get($a, $b = false)
    {
        if ($b !== true) {
            $value = $a * 5;
            if ($value === 'hello') {
                return false;
            }
            return $value;
        }
        if ($a === true) {
            return false;
        }

        return true;
    }
}

?>
