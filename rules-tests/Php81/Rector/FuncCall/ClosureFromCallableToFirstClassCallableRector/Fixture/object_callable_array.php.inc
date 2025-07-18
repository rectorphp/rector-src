<?php

Closure::fromCallable([$obj, 'method']);

?>
-----
<?php

$obj->method(...);

?>
