<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\FirstNamespace\SomeServiceClass;

final class RenameClassWithSameNameButDifferentNamespace
{
    public function run(): void
    {
        $someService = new SomeServiceClass();
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\SecondNamespace\SomeServiceClass;

final class RenameClassWithSameNameButDifferentNamespace
{
    public function run(): void
    {
        $someService = new SomeServiceClass();
    }
}

?>
