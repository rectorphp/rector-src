<?php

/** @var Zend_View $this */
?>
<div><?php echo $this->filter ;?></div>
-----
<?php

/** @var Zend_View $this */
/**
 * @var string $container
 */
echo 'this is new stmt after Nop';?>
<div><?php echo $this->filter ;?></div>