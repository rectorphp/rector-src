<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ObjectParamTypeByMethodCallTypeRector\Fixture;

use stdClass;

final class ObjectParam
{
    public function go($value)
    {
        $this->use($value);
    }

    private function use(stdClass $value): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ObjectParamTypeByMethodCallTypeRector\Fixture;

use stdClass;

final class ObjectParam
{
    public function go(\stdClass $value)
    {
        $this->use($value);
    }

    private function use(stdClass $value): void
    {
    }
}

?>
