<?php

namespace Rector\Tests\DeadCode\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector\Fixture;

class SkipBoolVersion
{
    public function run()
    {
        if (version_compare(PHP_VERSION, true, '<')) {
            return 'a';
        } else {
            return 'b';
        }
    }
}

?>
