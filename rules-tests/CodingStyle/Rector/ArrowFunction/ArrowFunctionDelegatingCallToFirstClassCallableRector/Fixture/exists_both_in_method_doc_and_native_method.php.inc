<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

/**
 * @method static array test(mixed $value)
 */
class ExistsBothInMethodDocAndNativeMethod
{
    public static function test(mixed $value)
    {
        return $value;
    }

    public function callTest()
    {
        $test = array_map(fn($value) => ExistsBothInMethodDocAndNativeMethod::test($value), [1, 2, 3, 4]);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

/**
 * @method static array test(mixed $value)
 */
class ExistsBothInMethodDocAndNativeMethod
{
    public static function test(mixed $value)
    {
        return $value;
    }

    public function callTest()
    {
        $test = array_map(ExistsBothInMethodDocAndNativeMethod::test(...), [1, 2, 3, 4]);
    }
}

?>
