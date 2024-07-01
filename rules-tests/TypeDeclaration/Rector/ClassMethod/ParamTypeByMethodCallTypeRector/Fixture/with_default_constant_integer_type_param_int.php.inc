<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class WithDefaultConstantIntegerTypeParamInt
{
    public function go($value = 1)
    {
        $this->execute($value);
    }

    private function execute(int $value)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class WithDefaultConstantIntegerTypeParamInt
{
    public function go(int $value = 1)
    {
        $this->execute($value);
    }

    private function execute(int $value)
    {
    }
}

?>
