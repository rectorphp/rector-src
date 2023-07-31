<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

class SomeClass
{
    public function resolve($item)
    {
        return $item . ' world';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

class SomeClass
{
    public function resolve(string $item)
    {
        return $item . ' world';
    }
}

?>
