<?php echo 'some expression'; ?>
<div>some text</div>
<?php
if ($a === 1) {
}
?>
-----
<?php

echo 'some expression';
?>
<div>some text</div>
<?php 
echo 'this is new stmt before if';
if ($a === 1) {
}
