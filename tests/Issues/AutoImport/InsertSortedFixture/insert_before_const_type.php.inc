<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use function f;
use const FOO;

new B();
f();
$b = \Bar\BAR;
$a = FOO;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use function f;
use const Bar\BAR;
use const FOO;

new B();
f();
$b = BAR;
$a = FOO;

?>
