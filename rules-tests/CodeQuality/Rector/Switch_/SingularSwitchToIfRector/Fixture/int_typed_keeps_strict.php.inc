<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SingularSwitchToIfRector\Fixture;

final class IntTypedKeepsStrict
{
    public function run(int $value)
    {
        switch ($value) {
            case 100:
                return 'hit';
        }

        return 'miss';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SingularSwitchToIfRector\Fixture;

final class IntTypedKeepsStrict
{
    public function run(int $value)
    {
        if ($value === 100) {
            return 'hit';
        }

        return 'miss';
    }
}

?>
