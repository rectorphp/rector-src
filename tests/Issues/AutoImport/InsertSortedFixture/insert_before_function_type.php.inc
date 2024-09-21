<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use function f;
use const FOO;

\Foobar\A();
new B();
f();
$a = FOO;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use function Foobar\A;
use function f;
use const FOO;

A();
new B();
f();
$a = FOO;

?>
