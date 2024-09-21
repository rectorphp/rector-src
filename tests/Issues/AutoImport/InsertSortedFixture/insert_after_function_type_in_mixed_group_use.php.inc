<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, function f, const FOO};

new B();
f();
\Foobar\x();
$a = FOO;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, function f, function x, const FOO};

new B();
f();
x();
$a = FOO;

?>
