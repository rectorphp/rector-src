<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use function f;
use const FOO;

new \Foobar\A();
new B();
f();
$a = FOO;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A;
use Foobar\B;
use function f;
use const FOO;

new A();
new B();
f();
$a = FOO;

?>
