<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\FirstNamespace\FirstOriginalClass as AliasedClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\SecondNamespace;

class ClassImportedAsAlias
{
    public function run()
    {
        $aliasedClass = new AliasedClass();
        $secondClass = new SecondNamespace\SecondOriginalClass();
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\SecondNamespace\SecondOriginalClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\FirstNamespace\FirstOriginalClass as AliasedClass;

class ClassImportedAsAlias
{
    public function run()
    {
        $aliasedClass = new AliasedClass();
        $secondClass = new SecondOriginalClass();
    }
}

?>
