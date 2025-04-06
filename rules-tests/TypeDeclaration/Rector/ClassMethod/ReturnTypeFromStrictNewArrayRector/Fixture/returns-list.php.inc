<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class ReturnsList
{
    function doFoo(int $i) {
        $a = [];
        $a[] = $i;
        return $a;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class ReturnsList
{
    /**
     * @return list<int>
     */
    function doFoo(int $i): array {
        $a = [];
        $a[] = $i;
        return $a;
    }
}

?>
