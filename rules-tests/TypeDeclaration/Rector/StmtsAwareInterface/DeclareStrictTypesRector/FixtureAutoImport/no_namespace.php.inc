<?php

// no namespace on purpose to reproduce issue
\Foo\Bar::BAZ;

?>
-----
<?php

declare(strict_types=1);

use Foo\Bar;

// no namespace on purpose to reproduce issue
Bar::BAZ;
