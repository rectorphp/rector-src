<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class InInnerFunction
{
    public function getValue($number)
    {
        function foo($number)
        {
            return $number ? 100 : 500;
        }

        echo foo($number);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class InInnerFunction
{
    public function getValue($number)
    {
        function foo($number): int
        {
            return $number ? 100 : 500;
        }

        echo foo($number);
    }
}

?>
