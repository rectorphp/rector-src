<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A, Foobar\C, Foobar\E;

new A();
new \Foobar\B();
new C();
new \Foobar\D();
new E();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A, Foobar\B, Foobar\C, Foobar\D, Foobar\E;

new A();
new B();
new C();
new D();
new E();

?>
