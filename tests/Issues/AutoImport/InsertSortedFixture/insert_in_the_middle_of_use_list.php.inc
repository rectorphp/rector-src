<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A;
use Foobar\C;

new A();
new \Foobar\B();
new C();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A;
use Foobar\B;
use Foobar\C;

new A();
new B();
new C();

?>
