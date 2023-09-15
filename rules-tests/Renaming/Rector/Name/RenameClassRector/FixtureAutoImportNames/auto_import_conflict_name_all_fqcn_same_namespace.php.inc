<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

final class AutoImportConflictNameAllFqcnSameNamespace
{
    /**
     * @return \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames\SomeShort|\SomeShort
     */
    public function bar()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

final class AutoImportConflictNameAllFqcnSameNamespace
{
    /**
     * @return SomeShort|\SomeShort
     */
    public function bar()
    {
    }
}

?>
