<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class NullableSelfFinalClass
{
    public function run()
    {
        if (rand(0, 1)) {
            return null;
        }

        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class NullableSelfFinalClass
{
    public function run(): ?self
    {
        if (rand(0, 1)) {
            return null;
        }

        return $this;
    }
}

?>
