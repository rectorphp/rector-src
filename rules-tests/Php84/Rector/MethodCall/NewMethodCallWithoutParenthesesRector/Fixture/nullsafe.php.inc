<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class Nullsafe
{
    public function run()
    {
        (new Request())?->withMethod('GET');
        $uri = (new Request())?->uri;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class Nullsafe
{
    public function run()
    {
        new Request()?->withMethod('GET');
        $uri = new Request()?->uri;
    }
}

?>
