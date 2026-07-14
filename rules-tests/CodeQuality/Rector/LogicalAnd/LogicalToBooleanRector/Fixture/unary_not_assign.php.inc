<?php

$y = 0;
!$y = 42 and true;

?>
-----
<?php

$y = 0;
!($y = 42) && true;

?>
