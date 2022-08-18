<?php

namespace Rector\Core\Tests\Issues\UnionArrayAutoImport\Fixture;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;

final class Fixture
{
    /**
     * @param Stmt[] $stmts
     */
    public function run(array $stmts): array
    {
        $expressions = [];

        foreach ($stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                break;
            }

            $expressions[] = $stmt;
        }

        return $expressions;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\UnionArrayAutoImport\Fixture;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;

final class Fixture
{
    /**
     * @param Stmt[] $stmts
     * @return Expression[]
     */
    public function run(array $stmts): array
    {
        $expressions = [];

        foreach ($stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                break;
            }

            $expressions[] = $stmt;
        }

        return $expressions;
    }
}

?>
