<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A, Foobar\D;

new A();
new \Foobar\B();
new \Foobar\C();
new D();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A, Foobar\B, Foobar\C, Foobar\D;

new A();
new B();
new C();
new D();

?>
