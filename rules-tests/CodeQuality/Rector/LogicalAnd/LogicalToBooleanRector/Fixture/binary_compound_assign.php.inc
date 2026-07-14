<?php

$x = 0;
2 * $x += 42 and true;

?>
-----
<?php

$x = 0;
2 * ($x += 42) && true;

?>
