<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use MyNamespace\MyClass

/**
 * @template T of MyClass
 */
interface MyServiceInterface
{
}
?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use MyNewNamespace\MyNewClass;

/**
 * @template T of MyNewClass
 */
interface MyServiceInterface
{
}
