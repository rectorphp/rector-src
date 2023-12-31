<?php

namespace Rector\Tests\Issues\ContinueToBreakSwitch\Fixture;

final class Fixture
{
    public function process() {
        while ($something = \random_int(0, 4)) {
            switch ($something) {
                case 1:
                    return $something;
                case 2:
                    continue;
                default:
                    \error_log('error');
                    throw new \Exception('error');
            }
        }

        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ContinueToBreakSwitch\Fixture;

final class Fixture
{
    public function process() {
        while ($something = \random_int(0, 4)) {
            switch ($something) {
                case 1:
                    return $something;
                case 2:
                    break;
                default:
                    \error_log('error');
                    throw new \Exception('error');
            }
        }

        return null;
    }
}

?>
