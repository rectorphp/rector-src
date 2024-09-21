<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{A, B};

new A();
new B();
new \Foo\C();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foo\C;
use Foobar\{A, B};

new A();
new B();
new C();

?>
