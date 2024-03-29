<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class NativeMethodCallReturningUnionFalse
{
    public function run()
    {
        return ob_get_clean();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class NativeMethodCallReturningUnionFalse
{
    public function run(): string|false
    {
        return ob_get_clean();
    }
}

?>
