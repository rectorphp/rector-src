<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

class SomeClass
{
    public function resolve($item)
    {
        return $item['name'];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

class SomeClass
{
    public function resolve(array $item)
    {
        return $item['name'];
    }
}

?>
