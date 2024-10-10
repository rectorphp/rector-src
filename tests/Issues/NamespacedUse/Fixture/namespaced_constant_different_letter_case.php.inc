<?php

use const Foo\Bar;
use const Baz\bar;

var_dump(bar);

?>
-----
<?php

use const Baz\bar;

var_dump(bar);

?>
