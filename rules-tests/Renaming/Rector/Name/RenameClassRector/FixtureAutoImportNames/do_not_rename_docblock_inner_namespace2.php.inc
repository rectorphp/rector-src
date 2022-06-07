<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use CompanyB\Foo as Storage;

class DoNotRenameDocblockInnerNamespace2
{
    /**
     * @var Storage
     */
    protected $storage;
}

?>
