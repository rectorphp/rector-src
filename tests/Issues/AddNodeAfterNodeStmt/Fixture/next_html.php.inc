<?php
if ($a === 1) {
}
?>
<div>some text</div>
-----
<?php

if ($a === 1) {
}
echo 'this is new stmt after if';
?>
<div>some text</div>
