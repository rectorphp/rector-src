<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StringReturnTypeFromStrictScalarReturnsRector\Fixture;

final class StringHerenowdoc
{
    public function run()
    {
        return <<<SQL
SELECT * FROM users
SQL;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StringReturnTypeFromStrictScalarReturnsRector\Fixture;

final class StringHerenowdoc
{
    public function run(): string
    {
        return <<<SQL
SELECT * FROM users
SQL;
    }
}

?>
