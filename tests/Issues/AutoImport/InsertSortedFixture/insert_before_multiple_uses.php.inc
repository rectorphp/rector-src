<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B, Foobar\C;

new \Foobar\A();
new B();
new C();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A;
use Foobar\B, Foobar\C;

new A();
new B();
new C();

?>
