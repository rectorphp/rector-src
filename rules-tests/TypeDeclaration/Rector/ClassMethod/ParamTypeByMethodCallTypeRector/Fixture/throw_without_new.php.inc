<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class ThrowWithoutNew
{
    public function execute(array $value): void {
    }

    public function set($value, \Throwable $t) {
    	if (rand(0, 1)) {
            return $this->execute($value);
        }

        throw $t;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class ThrowWithoutNew
{
    public function execute(array $value): void {
    }

    public function set(array $value, \Throwable $t) {
    	if (rand(0, 1)) {
            return $this->execute($value);
        }

        throw $t;
    }
}

?>
