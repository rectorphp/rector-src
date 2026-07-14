<?php

$t = 0;
(int) $t = 42 and true;

?>
-----
<?php

$t = 0;
(int) ($t = 42) && true;

?>
