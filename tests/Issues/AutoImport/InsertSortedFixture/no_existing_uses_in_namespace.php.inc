<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

new \Foo\Bar();

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foo\Bar;

new Bar();

?>
