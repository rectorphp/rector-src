<div>some text</div>
<?php
if ($a === 1) {
}
?>
-----
<div>some text</div>
<?php
echo 'this is new stmt before if';
if ($a === 1) {
}
?>
