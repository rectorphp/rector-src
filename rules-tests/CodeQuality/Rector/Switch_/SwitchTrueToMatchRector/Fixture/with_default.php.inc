<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToMatchRector\Fixture;

class WithDefault
{
    public function run($value)
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
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToMatchRector\Fixture;

class WithDefault
{
    public function run($value)
    {
        return match (true) {
            $value === 0 => 'no',
            $value === 1 => 'yes',
            $value === 2 => 'maybe',
            default => 'nevermind',
        };
    }
}

?>
