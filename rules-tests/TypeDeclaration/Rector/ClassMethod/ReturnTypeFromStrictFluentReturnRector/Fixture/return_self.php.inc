<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class ReturnSelf
{
    private $foo = 'bar';

    public function test()
    {
        $obj = new self();
        $obj->foo = 'foo';

        return $obj;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class ReturnSelf
{
    private $foo = 'bar';

    public function test(): self
    {
        $obj = new self();
        $obj->foo = 'foo';

        return $obj;
    }
}

?>
