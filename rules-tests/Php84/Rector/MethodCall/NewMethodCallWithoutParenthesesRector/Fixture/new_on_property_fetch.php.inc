<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class NewOnPropertyFetch
{
    public function run()
    {
        $message = (new Regex('some-pattern'))->message;
        $nested = (new Request())->uri->path;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class NewOnPropertyFetch
{
    public function run()
    {
        $message = new Regex('some-pattern')->message;
        $nested = new Request()->uri->path;
    }
}

?>
