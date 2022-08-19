<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector\Fixture;

use Rector\Tests\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector\Source\DreamSearch;

final class MyForeachCommand
{
    protected function execute(): void
    {
        foreach (range(1, 2) as $i) {
            $search = new DreamSearch();
            $search->reachIt();
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector\Fixture;

use Rector\Tests\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector\Source\DreamSearch;

final class MyForeachCommand
{
    protected function execute(): void
    {
        foreach (range(1, 2) as $i) {
            $dreamSearch = new DreamSearch();
            $dreamSearch->reachIt();
        }
    }
}

?>
