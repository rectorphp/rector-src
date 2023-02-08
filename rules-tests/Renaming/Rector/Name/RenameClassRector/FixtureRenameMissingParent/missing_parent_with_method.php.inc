<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureRenameMissingParent;

use Missing\ParentClass;

class MissingParent extends ParentClass
{
    public function foo(): void
    {
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureRenameMissingParent;

class MissingParent extends \Renamed\Missing\ParentWithMethod
{
    public function foo(): void
    {
    }
}

?>
