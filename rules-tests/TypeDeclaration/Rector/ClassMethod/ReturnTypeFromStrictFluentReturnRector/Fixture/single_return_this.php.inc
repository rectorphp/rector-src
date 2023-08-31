<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class SingleReturnThis
{
    public function test()
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class SingleReturnThis
{
    public function test(): self
    {
        return $this;
    }
}

?>