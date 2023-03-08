<?php

declare(strict_types=1);

 /**
 * Docblock
 */
final class NoNamespaceWithStrictTypesNoExistingUse
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

declare(strict_types=1);

use Foobar\B;

 /**
 * Docblock
 */
final class NoNamespaceWithStrictTypesNoExistingUse
{
    public function run()
    {
        new A();
        new B();
    }
}

?>
