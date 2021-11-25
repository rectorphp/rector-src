<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use App\Model\OldClass;

/**
 * @template T of OldClass
 */
interface MyServiceInterface
{
}
?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use App\Model\OldClass;

/**
 * @template T of NewClass
 */
interface MyServiceInterface
{
}
