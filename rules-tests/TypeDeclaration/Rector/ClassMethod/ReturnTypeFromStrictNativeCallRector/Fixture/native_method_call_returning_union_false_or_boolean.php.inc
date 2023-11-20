<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class NativeMethodCallReturningMergedUnionFalseOrBoolean
{
    public function run(string $needle, array $haystack)
    {
        if (rand(0,1)) {
            return ob_get_clean();
        }

        return in_array($needle, $haystack, true);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class NativeMethodCallReturningMergedUnionFalseOrBoolean
{
    public function run(string $needle, array $haystack): string|bool
    {
        if (rand(0,1)) {
            return ob_get_clean();
        }

        return in_array($needle, $haystack, true);
    }
}

?>
