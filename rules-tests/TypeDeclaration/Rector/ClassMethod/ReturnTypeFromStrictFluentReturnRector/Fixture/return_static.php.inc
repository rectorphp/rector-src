<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

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

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

class ReturnStatic
{
    private $foo = 'bar';

    public function test(): static
    {
        $obj = new static();
        $obj->foo = 'foo';

        return $obj;
    }
}

?>
