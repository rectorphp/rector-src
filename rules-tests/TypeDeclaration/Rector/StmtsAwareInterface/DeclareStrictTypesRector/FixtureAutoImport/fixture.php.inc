<?php

// no namespace on purpose to reproduce issue

\Foo\Bar::BAZ;

?>
-----
<?php

declare(strict_types=1);

// no namespace on purpose to reproduce issue

use Foo\Bar;
Bar::BAZ;
