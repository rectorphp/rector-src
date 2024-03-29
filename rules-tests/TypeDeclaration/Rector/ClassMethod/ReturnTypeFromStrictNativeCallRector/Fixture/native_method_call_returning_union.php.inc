<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class NativeMethodCallReturningUnion
{
    public function run()
    {
        return time_nanosleep(100, 100);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class NativeMethodCallReturningUnion
{
    public function run(): array|bool
    {
        return time_nanosleep(100, 100);
    }
}

?>
