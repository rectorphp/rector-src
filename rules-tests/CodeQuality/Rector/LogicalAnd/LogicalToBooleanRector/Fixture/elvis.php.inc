<?php

$z = 0;
($z ?: 7) and true;

?>
-----
<?php

$z = 0;
($z ?: 7) && true;

?>
