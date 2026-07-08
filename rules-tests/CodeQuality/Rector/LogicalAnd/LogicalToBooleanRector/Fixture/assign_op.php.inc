<?php

$x = 0;
($x += 42) and true;

?>
-----
<?php

$x = 0;
($x += 42) && true;

?>
