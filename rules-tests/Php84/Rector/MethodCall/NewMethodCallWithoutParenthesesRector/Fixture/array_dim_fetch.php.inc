<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class ArrayDimFetch
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

final class ArrayDimFetch
{
    public function run()
    {
        $value = new \ArrayObject(['key' => 'value'])['key'];
    }
}

?>
