<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

class RemoveOnCallerViaSelf
{
	private static function just_a_test(
        string $foo,
        array $bar,
        $used_parameter
    ): void {
		\external::whatever($foo, $used_parameter);
	}

	public static function trigger(): void
	{
		self::just_a_test('hello', [], 'test');
	}
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

class RemoveOnCallerViaSelf
{
	private static function just_a_test(
        string $foo,
        $used_parameter
    ): void {
		\external::whatever($foo, $used_parameter);
	}

	public static function trigger(): void
	{
		self::just_a_test('hello', 'test');
	}
}

?>