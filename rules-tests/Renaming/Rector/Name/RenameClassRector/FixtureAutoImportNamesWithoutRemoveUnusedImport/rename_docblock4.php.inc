<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNamesWithoutRemoveUnusedImport;

use DateTime;

if ($var instanceof DateTime) {
    /** @var DateTime */
    $var->format('Y-m-d');
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNamesWithoutRemoveUnusedImport;

use DateTimeInterface;

if ($var instanceof DateTimeInterface) {
    /** @var DateTimeInterface */
    $var->format('Y-m-d');
}

?>
