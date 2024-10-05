<?php

namespace Rector\Tests\Issues\NamespacedUse\Fixture;

use const Foo\Bar;
use const Baz\bar;

bar::class;

?>
-----
<?php

namespace Rector\Tests\Issues\NamespacedUse\Fixture;

use const Baz\bar;

bar::class;

?>
