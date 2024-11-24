<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass;

/** @var OldClass $var */
$var = get2();

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

/** @var \Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass $var */
$var = get2();

?>
