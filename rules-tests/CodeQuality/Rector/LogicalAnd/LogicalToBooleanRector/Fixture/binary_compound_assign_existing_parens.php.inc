<?php

$s = 0;
2 * ($s += 42) and true;

?>
-----
<?php

$s = 0;
2 * ($s += 42) && true;

?>
