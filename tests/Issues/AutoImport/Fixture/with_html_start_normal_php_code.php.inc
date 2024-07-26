<?php

$this->headTitle('Transaction Detail');

?>

<div>
    <?=new \Rector\Tests\Issues\AutoImport\Source\SomeClass()?>
</div>
-----
<?php

use Rector\Tests\Issues\AutoImport\Source\SomeClass;
$this->headTitle('Transaction Detail');

?>

<div>
    <?=new SomeClass()?>
</div>