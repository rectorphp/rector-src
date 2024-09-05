<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Class_ as ClassOrig;
use PhpParser\Node;

final class ConflictLastNameAliasedUsed
{
    /**
     * @param Node\Stmt\Class_ $param
     */
    public function run($param): void
    {
        new ClassOrig();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use PhpParser\Node\Stmt\Class_;
use Class_ as ClassOrig;

final class ConflictLastNameAliasedUsed
{
    /**
     * @param Class_ $param
     */
    public function run($param): void
    {
        new ClassOrig();
    }
}

?>
