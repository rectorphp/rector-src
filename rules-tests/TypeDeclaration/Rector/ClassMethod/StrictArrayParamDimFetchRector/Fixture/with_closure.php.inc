<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

class WithClosure
{
    public function resolve($item)
    {
        return function ($item) {
            return $item['name'];
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

class WithClosure
{
    public function resolve($item)
    {
        return function (array $item) {
            return $item['name'];
        };
    }
}

?>
