<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

use Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Source\SomeExtendedClass;

class FooBar extends SomeExtendedClass
{
    public function someMethod()
    {
        $this->something['key'];
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

use Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Source\SomeExtendedClass;

class FooBar extends SomeExtendedClass
{
    public function someMethod()
    {
        $this->something->make('key');
    }
}

?>
