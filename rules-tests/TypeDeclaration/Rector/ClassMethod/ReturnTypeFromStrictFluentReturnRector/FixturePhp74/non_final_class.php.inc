<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\FixturePhp74;

class NonFinalClass
{
    public function test()
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\FixturePhp74;

class NonFinalClass
{
    public function test(): self
    {
        return $this;
    }
}

?>