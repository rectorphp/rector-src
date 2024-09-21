<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{B, C};

new \Foobar\A();
new B();
new C();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A;
use Foobar\{B, C};

new A();
new B();
new C();

?>
