<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class MaybeNotReturnedBeforeLabel
{
    public function run()
    {
        if (rand(0, 1)) {
            return 1;
        } elseif (rand(0, 2)) {
            return null;
        } else {
            // nothing to do here...
        }

        unusedLabel:
            echo 'unused';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class MaybeNotReturnedBeforeLabel
{
    public function run()
    {
        if (rand(0, 1)) {
            return 1;
        } elseif (rand(0, 2)) {
            return null;
        } else {
            // nothing to do here...
        }

        unusedLabel:
            echo 'unused';
            return null;
    }
}

?>
