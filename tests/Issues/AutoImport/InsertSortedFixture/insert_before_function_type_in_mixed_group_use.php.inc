<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, function f, const FOO};

new B();
\Foobar\A();
f();
$a = FOO;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, function A, function f, const FOO};

new B();
A();
f();
$a = FOO;

?>
