<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Some\Class_;
use PhpParser\Node;

final class ConflictLastNameUnused
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
final class ConflictLastNameUnused
{
    /**
     * @param Class_ $param
     */
    public function run($param): void
    {
    }
}

?>
