<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass as OldSomeClass;

/** @var OldSomeClass $var */
$var = get();

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass as OldSomeClass;

/** @var \Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass $var */
$var = get();

?>
