<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class MultilineParentheses
{
    public function run()
    {
        $message = (
            new Regex('some-pattern')
        )->message;
        $value = (new Regex('some-pattern') /* comment */)->message;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class MultilineParentheses
{
    public function run()
    {
        $message = 
            new Regex('some-pattern')
        ->message;
        $value = new Regex('some-pattern') /* comment */->message;
    }
}

?>
