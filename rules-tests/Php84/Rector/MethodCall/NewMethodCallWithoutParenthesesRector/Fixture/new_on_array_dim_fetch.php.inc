<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class NewOnArrayDimFetch
{
    public function run()
    {
        $value = (new \ArrayObject(['key' => 'value']))['key'];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class NewOnArrayDimFetch
{
    public function run()
    {
        $value = new \ArrayObject(['key' => 'value'])['key'];
    }
}

?>
