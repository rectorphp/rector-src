<?php

/**
 * License
 */

use Foobar\A;

/**
 * Docblock
 */
final class NoNamespace
{
    public function run()
    {
        new A();
        new \Foobar\B();
    }
}

?>
-----
<?php

/**
 * License
 */
use Foobar\B;
use Foobar\A;

/**
 * Docblock
 */
final class NoNamespace
{
    public function run()
    {
        new A();
        new B();
    }
}

?>
