<?php

true and ($x = 42) and false;

var_dump($x);

?>
-----
<?php

true && ($x = 42) && false;

var_dump($x);

?>
