<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

// real-world false positive (spiral/framework ReflectionFileTest::deadend):
// the method body is read back via new ReflectionFile(__FILE__) and its
// function invocations are asserted - so it is "used" only at runtime via
// self-reflection, which static analysis cannot see.
final class UsedViaSelfReflection
{
    public function testInvocations(): void
    {
        $reflection = new ReflectionFile(__FILE__);
        foreach ($reflection->getInvocations() as $invocation) {
            // asserts test_function_a / test_function_b are found
        }
    }

    private function deadend(): void
    {
        $a = $b = null;
        test_function_a($this, $a + $b);
        test_function_b("string", 123);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

// real-world false positive (spiral/framework ReflectionFileTest::deadend):
// the method body is read back via new ReflectionFile(__FILE__) and its
// function invocations are asserted - so it is "used" only at runtime via
// self-reflection, which static analysis cannot see.
final class UsedViaSelfReflection
{
    public function testInvocations(): void
    {
        $reflection = new ReflectionFile(__FILE__);
        foreach ($reflection->getInvocations() as $invocation) {
            // asserts test_function_a / test_function_b are found
        }
    }
}

?>
