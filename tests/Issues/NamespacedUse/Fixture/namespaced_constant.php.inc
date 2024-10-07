<?php

use const Foo\Bar;
use const Baz\bar;

bar::class;

?>
-----
<?php

use const Baz\bar;

bar::class;

?>
