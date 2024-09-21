<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, function f, const FOO};

new B();
f();
$f = \Foobar\BAR;
$a = FOO;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, function f, const BAR, const FOO};

new B();
f();
$f = BAR;
$a = FOO;

?>
