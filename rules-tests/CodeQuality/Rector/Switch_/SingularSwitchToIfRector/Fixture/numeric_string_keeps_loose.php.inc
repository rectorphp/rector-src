<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SingularSwitchToIfRector\Fixture;

final class NumericStringKeepsLoose
{
    public function run(string $value)
    {
        switch ($value) {
            case '100':
                return 'hit';
        }

        return 'miss';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SingularSwitchToIfRector\Fixture;

final class NumericStringKeepsLoose
{
    public function run(string $value)
    {
        if ($value == '100') {
            return 'hit';
        }

        return 'miss';
    }
}

?>
