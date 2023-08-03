<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

final class WithInnerClosure
{
    public function resolve($item)
    {
        $result = $item * 100;

        function ($item) {
            return $item . ' world';
        };

        return $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

final class WithInnerClosure
{
    public function resolve($item)
    {
        $result = $item * 100;

        function (string $item) {
            return $item . ' world';
        };

        return $result;
    }
}

?>
