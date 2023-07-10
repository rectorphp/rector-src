<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use \Company2\{ Bar };

final class SkipRenameDocblockSameNamespaceLastGroup
{
    /**
     * @param Bar $foo
     */
    public function importedClassViaSameNamespace($foo)
    {
    }
}
