<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class MultiReturnThis
{
    private $foo = 'bar';

    public function test()
    {
        if (rand(0, 1)) {
            $this->foo = 'foo';
            return $this;
        }

        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class MultiReturnThis
{
    private $foo = 'bar';

    public function test(): self
    {
        if (rand(0, 1)) {
            $this->foo = 'foo';
            return $this;
        }

        return $this;
    }
}

?>