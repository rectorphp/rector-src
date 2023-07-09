<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use CompanyB\Queue;

final class ConflictWithUseStatementLastName
{
    public function run()
    {
        $queue = app(\Queue::class);
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use Illuminate\Support\Facades\Queue;

final class ConflictWithUseStatementLastName
{
    public function run()
    {
        $queue = app(Queue::class);
    }
}

?>
