<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use MyNamespace\MylegacyClass;

/**
 * @template T of MylegacyClass
 */
interface MyServiceInterface
{
    /**
     * @return T
     */
    public function get(): MylegacyClass;
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

/**
 * @template T of \MyNewNamespace\MyNewClass
 */
interface MyServiceInterface
{
    /**
     * @return T
     */
    public function get(): \MyNewNamespace\MyNewClass;
}

?>
