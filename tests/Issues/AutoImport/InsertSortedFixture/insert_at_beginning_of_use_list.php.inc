<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\B;
use Foobar\C;

new \Foobar\A();
new B();
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
