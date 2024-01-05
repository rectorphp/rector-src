<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

class BothIntegerAndStringIndex
{
    public function resolve($item)
    {
        if (rand(0, 1)) {
            return $item[0];
        }

        return $item['test'];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

class BothIntegerAndStringIndex
{
    public function resolve(array $item)
    {
        if (rand(0, 1)) {
            return $item[0];
        }

        return $item['test'];
    }
}

?>
