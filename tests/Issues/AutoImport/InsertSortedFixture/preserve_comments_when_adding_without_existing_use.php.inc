<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

/**
 * License
 */

/**
 * Docblock
 */
final class DemoFile
{
    public function run()
    {
        new \Foobar\A();
        new \Foobar\B();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

use Foobar\A;
use Foobar\B;

/**
 * License
 */
/**
 * Docblock
 */
final class DemoFile
{
    public function run()
    {
        new A();
        new B();
    }
}

?>
