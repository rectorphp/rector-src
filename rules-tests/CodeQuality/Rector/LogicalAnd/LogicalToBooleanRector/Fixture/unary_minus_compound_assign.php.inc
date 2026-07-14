<?php

$z = 0;
-$z += 42 and true;

?>
-----
<?php

$z = 0;
-($z += 42) && true;

?>
