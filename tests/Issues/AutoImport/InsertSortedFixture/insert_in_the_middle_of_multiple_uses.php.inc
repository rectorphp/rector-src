<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A, Foobar\C;

new A();
new \Foobar\B();
new C();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A, Foobar\B, Foobar\C;

new A();
new B();
new C();

?>
