<?php

namespace Rector\Tests\Renaming\Rector\FuncCall\RenameFunctionRector\Fixture;

class InFor {
    function process($file) {
		for ($i=0;$n=sizeof($files),$i<$n;$i++) {
			$products_image_name = $files[$i]['text'];
		}
	}
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\FuncCall\RenameFunctionRector\Fixture;

class InFor {
    function process($file) {
		for ($i=0;$n=count($files),$i<$n;$i++) {
			$products_image_name = $files[$i]['text'];
		}
	}
}

?>
