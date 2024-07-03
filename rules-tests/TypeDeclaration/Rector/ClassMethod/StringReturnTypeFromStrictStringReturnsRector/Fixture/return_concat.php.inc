<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StringReturnTypeFromStrictStringReturnsRector\Fixture;

final class SkipReturnConcat
{
    public function run($value)
    {
        return $value . '/' ;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StringReturnTypeFromStrictStringReturnsRector\Fixture;

final class SkipReturnConcat
{
    public function run($value): string
    {
        return $value . '/' ;
    }
}

?>
