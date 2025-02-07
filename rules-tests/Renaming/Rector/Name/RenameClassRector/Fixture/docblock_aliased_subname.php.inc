<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source as SomeSource;

/** @var SomeSource\OldClass $var */
$var = get();

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

/** @var \Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass $var */
$var = get();

?>
