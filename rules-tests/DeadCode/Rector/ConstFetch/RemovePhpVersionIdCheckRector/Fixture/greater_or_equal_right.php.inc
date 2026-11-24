<?php

namespace Rector\Tests\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector\Fixture;

class GreaterOrEqualRight
{
    public function run()
    {
        if (70400 >= PHP_VERSION_ID) {
            return;
        }
        echo 'do something';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector\Fixture;

class GreaterOrEqualRight
{
    public function run()
    {
        echo 'do something';
    }
}

?>
