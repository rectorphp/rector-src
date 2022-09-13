<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

final class DemoFile
{
	public $a = 0;

	public $b = 0;

	public function run() { ?>
			<strong>A:</strong> <?php $this->a; ?><br>
			<strong>B:</strong> <?php $this->b; ?><br>
	<?php }
}

?>
