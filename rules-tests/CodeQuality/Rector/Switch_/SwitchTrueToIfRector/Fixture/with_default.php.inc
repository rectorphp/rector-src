<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToIfRector\Fixture;

class WithDefault
{
    public function run()
    {
        switch (true) {
            case $value === 0:
                return 'no';
            case $value === 1:
                return 'yes';
            case $value === 2:
                return 'maybe';
            default:
                return 'nevermind';
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToIfRector\Fixture;

class WithDefault
{
    public function run()
    {
        if ($value === 0) {
            return 'no';
        }
        if ($value === 1) {
            return 'yes';
        }
        if ($value === 2) {
            return 'maybe';
        }
        return 'nevermind';
    }
}

?>
