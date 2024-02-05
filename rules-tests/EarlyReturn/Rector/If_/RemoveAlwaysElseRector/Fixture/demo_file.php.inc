<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

final class DemoFile
{
    public function run(bool $param)
    {
        if ( isset( $_POST['a'] ) ) {
			if ( $param === true ) {
				echo 'hello';
				exit;
			} else {
				// abc
				?>
				<strong><?php echo 'world'; ?>:</strong><?php echo 'acme'; ?>-
				<?php echo 'foo';
			}
		}
    }
}
?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

final class DemoFile
{
    public function run(bool $param)
    {
        if ( isset( $_POST['a'] ) ) {
			if ( $param === true ) {
				echo 'hello';
				exit;
			}
				// abc
				?>
				<strong><?php echo 'world'; ?>:</strong><?php echo 'acme'; ?>-
				<?php echo 'foo';
		}
    }
}
?>
