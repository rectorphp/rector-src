<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class InnerClassMethod
{
    public function test()
    {
        new class {
            public function run()
            {
                return $this;
            }
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class InnerClassMethod
{
    public function test()
    {
        new class {
            public function run(): self
            {
                return $this;
            }
        };
    }
}

?>
