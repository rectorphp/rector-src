<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use function f;
use const FOO;

\test\A();
new B();
f();
$a = FOO;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use function f;
use function test\A;
use const FOO;

A();
new B();
f();
$a = FOO;

?>
