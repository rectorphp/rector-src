<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class WithDefaultSameType
{
    public function go($value = 1)
    {
        return $this->execute($value);
    }

    private function execute(int $value)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class WithDefaultSameType
{
    public function go(int $value = 1)
    {
        return $this->execute($value);
    }

    private function execute(int $value)
    {
    }
}

?>