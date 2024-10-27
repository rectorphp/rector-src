<?php
use Rector\Tests\Transform\Rector\Assign\PropertyFetchToMethodCallRector\Source\Generator;

$fn = function (Generator $o) {echo $o->word; };

if (rand()) {
	$a = "
	
	";
	$b = <<<TXT
	
TXT;
	$c = <<<'TXT'
	
TXT;
	//
}
?>
	<?php if (rand()):
		//
		?>
	<?php endif; ?>
-----
<?php
use Rector\Tests\Transform\Rector\Assign\PropertyFetchToMethodCallRector\Source\Generator;

$fn = function (Generator $o) {echo $o->word(); };

if (rand()) {
	$a = "
	
	";
	$b = <<<TXT
	
TXT;
	$c = <<<'TXT'
	
TXT;
	//
}
?>
	<?php if (rand()):
		//
		?>
	<?php endif; ?>
