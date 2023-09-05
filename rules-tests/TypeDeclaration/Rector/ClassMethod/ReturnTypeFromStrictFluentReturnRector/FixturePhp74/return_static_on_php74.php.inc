<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\FixturePhp74;

class ReturnStatic
{
    private $foo = 'bar';

    public function test()
    {
        $obj = new static();
        $obj->foo = 'foo';

        return $obj;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\FixturePhp74;

class ReturnStatic
{
    private $foo = 'bar';

    public function test(): self
    {
        $obj = new static();
        $obj->foo = 'foo';

        return $obj;
    }
}

?>
