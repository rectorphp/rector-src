<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class Invoke
{
    public function run()
    {
        $result = (new Invokable())();
        $withArgs = (new Invokable(1))(2, 3);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class Invoke
{
    public function run()
    {
        $result = new Invokable()();
        $withArgs = new Invokable(1)(2, 3);
    }
}

?>
