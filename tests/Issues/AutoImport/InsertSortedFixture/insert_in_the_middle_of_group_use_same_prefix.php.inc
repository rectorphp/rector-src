<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{A, C};

new A();
new \Foobar\B();
new C();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\{A, B, C};

new A();
new B();
new C();

?>
