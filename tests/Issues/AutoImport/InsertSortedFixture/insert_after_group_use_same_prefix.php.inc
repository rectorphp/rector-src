<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{A, B};

new A();
new B();
new \Foobar\C();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{A, B};
use Foobar\C;

new A();
new B();
new C();

?>
