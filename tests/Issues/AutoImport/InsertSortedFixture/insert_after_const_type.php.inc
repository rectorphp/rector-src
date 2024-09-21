<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use function f;
use const FOO;

new B();
f();
$b = \Foobar\BAR;
$a = FOO;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use function f;
use const FOO;
use const Foobar\BAR;

new B();
f();
$b = BAR;
$a = FOO;

?>
