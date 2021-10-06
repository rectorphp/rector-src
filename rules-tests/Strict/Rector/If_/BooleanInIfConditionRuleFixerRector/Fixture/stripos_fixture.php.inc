<?php
if (stripos("abc", "ab")) {}
?>
-----
<?php
if (stripos("abc", "ab") !== false) {}
?>
