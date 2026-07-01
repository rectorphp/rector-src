<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayParamTypeByMethodCallTypeRector\Fixture;

final class NullableArray
{
    public function go($value)
    {
        $this->use($value);
    }

    private function use(?array $value): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayParamTypeByMethodCallTypeRector\Fixture;

final class NullableArray
{
    public function go(?array $value)
    {
        $this->use($value);
    }

    private function use(?array $value): void
    {
    }
}

?>
