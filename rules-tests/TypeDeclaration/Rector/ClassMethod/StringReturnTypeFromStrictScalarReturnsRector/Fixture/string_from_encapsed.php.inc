<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StringReturnTypeFromStrictScalarReturnsRector\Fixture;

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

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StringReturnTypeFromStrictScalarReturnsRector\Fixture;

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
