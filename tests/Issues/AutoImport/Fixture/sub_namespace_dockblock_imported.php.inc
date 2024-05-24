<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;

final class SubNamespaceImported
{

    /**
     * @param Node\Stmt\Class_ $node
     */
    public function test(Node $node)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;

final class SubNamespaceImported
{

    /**
     * @param Class_ $node
     */
    public function test(Node $node)
    {
    }
}

?>
