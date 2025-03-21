<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class Fixture
{
    public function run()
    {
        (new Request())->withMethod('GET')->withUri('/hello-world');
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class Fixture
{
    public function run()
    {
        new Request()->withMethod('GET')->withUri('/hello-world');
    }
}

?>
