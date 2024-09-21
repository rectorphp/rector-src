<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, C};

new \Test\A();
new B();
new C();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, C};
use Test\A;

new A();
new B();
new C();

?>
