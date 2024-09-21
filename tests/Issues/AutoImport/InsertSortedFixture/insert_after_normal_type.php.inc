<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use function f;
use const FOO;

new B();
new \Foobar\x();
f();
$a = FOO;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use Foobar\x;
use function f;
use const FOO;

new B();
new x();
f();
$a = FOO;

?>
