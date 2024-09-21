<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, function f, const FOO};

new \Foobar\t();
new B();
f();
$a = FOO;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, t, function f, const FOO};

new t();
new B();
f();
$a = FOO;

?>
