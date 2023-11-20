<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class NativeMethodCallReturningBooleanUnionFalse
{
    public function run(string $needle, array $haystack)
    {
        if (rand(0,1)) {
            return in_array($needle, $haystack, true);
        }

        return ob_get_clean();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class NativeMethodCallReturningBooleanUnionFalse
{
    public function run(string $needle, array $haystack): bool|string
    {
        if (rand(0,1)) {
            return in_array($needle, $haystack, true);
        }

        return ob_get_clean();
    }
}

?>
