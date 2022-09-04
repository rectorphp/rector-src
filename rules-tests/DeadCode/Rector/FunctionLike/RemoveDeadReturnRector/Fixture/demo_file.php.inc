<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector\Fixture;

final class DemoFile
{
	public function run()
	{ ?>
		<div>
			hello world	
		</div>
		<?php
		return;
	}
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector\Fixture;

final class DemoFile
{
	public function run()
	{ ?>
		<div>
			hello world	
		</div>
		<?php
	}
}

?>
