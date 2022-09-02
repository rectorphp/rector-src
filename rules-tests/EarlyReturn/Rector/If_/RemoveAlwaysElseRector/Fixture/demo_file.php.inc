<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

final class DemoFile
{
    public function run()
    { ?>
        <div>
            hello
        </div>
        <?php
        if ( rand( 0, 5 ) > 2 ) {
            return;
        } else {
            this is <?php echo "hello"; ?> world
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;
final class DemoFile
{
    public function run()
    { ?>
        <div>
            hello
        </div>
        <?php
        if ( rand( 0, 5 ) > 2 ) {
            return;
        }
        this is <?php echo "hello"; ?> world
    }
}

?>
