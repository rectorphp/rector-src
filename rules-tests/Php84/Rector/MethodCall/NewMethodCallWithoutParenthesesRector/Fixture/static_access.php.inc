<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class StaticAccess
{
    public function run()
    {
        (new Request())::create();
        $constant = (new Request())::METHOD_GET;
        $property = (new Request())::$defaultMethod;
        $className = (new Request())::class;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class StaticAccess
{
    public function run()
    {
        new Request()::create();
        $constant = new Request()::METHOD_GET;
        $property = new Request()::$defaultMethod;
        $className = new Request()::class;
    }
}

?>
