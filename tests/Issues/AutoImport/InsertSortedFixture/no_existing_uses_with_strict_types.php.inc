<?php

declare(strict_types=1);

new \Foo\Bar();

?>
-----
<?php

declare(strict_types=1);

use Foo\Bar;

new Bar();

?>
