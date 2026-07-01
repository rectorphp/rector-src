<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

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

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

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
