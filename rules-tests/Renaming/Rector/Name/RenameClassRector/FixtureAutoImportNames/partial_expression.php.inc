<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use PhpParser\Node;

class PartialExpression
{
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class];
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use PhpParser\Node\Stmt\Expression;

class PartialExpression
{
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }
}

?>
