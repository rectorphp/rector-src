<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, function f, const FOO};

new B();
f();
$a = FOO;
$f = \Foobar\XXX;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, function f, const FOO};
use const Foobar\XXX;

new B();
f();
$a = FOO;
$f = XXX;

?>
