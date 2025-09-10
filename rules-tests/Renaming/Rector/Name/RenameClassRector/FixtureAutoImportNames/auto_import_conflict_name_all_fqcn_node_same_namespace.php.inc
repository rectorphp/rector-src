<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

final class AutoImportConflictNameAllFqcnNoSameNamespace
{
    public function bar(): \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames\SomeShort|\SomeShort
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use SomeShort;

final class AutoImportConflictNameAllFqcnNoSameNamespace
{
    public function bar(): \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames\SomeShort|SomeShort
    {
    }
}

?>
