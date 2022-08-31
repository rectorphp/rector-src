<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

final class SkipElseInlineHTML
{
	public function run()
	{
		if ( rand( 0, 5 ) > 2 ) {
			return;
		} else { ?>
			this is <?php echo "hello"; ?> world
		<?php }
	}
}
