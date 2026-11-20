<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class DynamicVariableClassName
{
    public function run()
    {
        (new $className())->run();
        $value = (new static())->value;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class DynamicVariableClassName
{
    public function run()
    {
        new $className()->run();
        $value = new static()->value;
    }
}

?>
