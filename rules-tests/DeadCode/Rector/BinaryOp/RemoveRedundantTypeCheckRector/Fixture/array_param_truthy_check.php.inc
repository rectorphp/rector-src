<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class ArrayParamTruthyCheck
{
    public function buildWhereClauseFromArray($query, array $clauses, $expr = null): void
    {
        if ($clauses && is_array($clauses)) {
            return;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class ArrayParamTruthyCheck
{
    public function buildWhereClauseFromArray($query, array $clauses, $expr = null): void
    {
        if ($clauses) {
            return;
        }
    }
}

?>
