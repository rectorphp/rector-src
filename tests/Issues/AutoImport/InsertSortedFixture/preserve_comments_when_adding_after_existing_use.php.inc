<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

/**
 * License
 */

use Foobar\B;

/**
 * Docblock
 */
final class DemoFile
{
    public function run()
    {
        new \Foobar\A();
        new B();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\InsertSortedFixture;

/**
 * License
 */
use Foobar\A;
use Foobar\B;

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
