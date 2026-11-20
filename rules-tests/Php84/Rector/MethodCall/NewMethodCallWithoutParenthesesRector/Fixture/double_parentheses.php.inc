<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class DoubleParentheses
{
    public function run()
    {
        ((new Request()))->withMethod('GET');
        $message = (((new Regex('some-pattern'))))->message;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class DoubleParentheses
{
    public function run()
    {
        new Request()->withMethod('GET');
        $message = new Regex('some-pattern')->message;
    }
}

?>
