<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class SomeClass
{
    public function getValue($number)
    {
        return $number ? 100 : 500;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class SomeClass
{
    public function getValue($number): int
    {
        return $number ? 100 : 500;
    }
}

?>
