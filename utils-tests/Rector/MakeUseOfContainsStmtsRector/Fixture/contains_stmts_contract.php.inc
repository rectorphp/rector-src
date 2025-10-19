<?php

namespace Rector\Utils\Tests\Rector\MakeUseOfContainsStmtsRector\Fixture;

use PhpParser\Node\ContainsStmts;

final class ContaintsStmtsContract
{
    public function process(ContainsStmts $containsStmts): bool
    {
        if ($containsStmts->stmts === null) {
            return false;
        }

        return true;
    }
}

?>
-----
<?php

namespace Rector\Utils\Tests\Rector\MakeUseOfContainsStmtsRector\Fixture;

use PhpParser\Node\ContainsStmts;

final class ContaintsStmtsContract
{
    public function process(ContainsStmts $containsStmts): bool
    {
        if ($containsStmts->getStmts() === []) {
            return false;
        }

        return true;
    }
}

?>
