<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Some\Class_ as ClassOrig;
use PhpParser\Node;

final class ConflictLastNameAliased
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
final class ConflictLastNameAliased
{
    /**
     * @param Class_ $param
     */
    public function run($param): void
    {
    }
}

?>
