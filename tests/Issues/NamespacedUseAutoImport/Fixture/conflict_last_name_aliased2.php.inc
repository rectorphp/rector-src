<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Class_ as ClassOrig;
use PhpParser\Node;

final class ConflictLastNameAliased2
{
    /**
     * @param Node\Stmt\Class_ $param
     */
    public function run($param): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use PhpParser\Node\Stmt\Class_;
final class ConflictLastNameAliased2
{
    /**
     * @param Class_ $param
     */
    public function run($param): void
    {
    }
}

?>
