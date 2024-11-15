<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class FloatTernary
{
    public function getValue($number)
    {
        return $number ? 100.1 : 500.1;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class FloatTernary
{
    public function getValue($number): float
    {
        return $number ? 100.1 : 500.1;
    }
}

?>
