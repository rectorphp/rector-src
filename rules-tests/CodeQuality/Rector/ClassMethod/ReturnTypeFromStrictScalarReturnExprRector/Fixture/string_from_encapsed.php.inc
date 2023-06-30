<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class StringFromEncapsed
{
    public function run($table)
    {
        return <<<SQL
SELECT * FROM $table
SQL;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class StringFromEncapsed
{
    public function run($table): string
    {
        return <<<SQL
SELECT * FROM $table
SQL;
    }
}

?>
