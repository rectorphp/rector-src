<?php

namespace Rector\Tests\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector\Fixture;

class NoStmts
{
    public function run()
    {
        if (PHP_VERSION_ID > 70400) {
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector\Fixture;

class NoStmts
{
    public function run()
    {
    }
}

?>
