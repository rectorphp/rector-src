<?php

namespace Rector\Tests\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector\Fixture;

class GreaterLowerOrEqual
{
    public function run()
    {
        if (PHP_VERSION_ID >= 80000) {
            echo 'do something';
        }
    }

    public function run2()
    {
        if (80000 <= PHP_VERSION_ID) {
            echo 'do something';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector\Fixture;

class GreaterLowerOrEqual
{
    public function run()
    {
        echo 'do something';
    }

    public function run2()
    {
        echo 'do something';
    }
}

?>
