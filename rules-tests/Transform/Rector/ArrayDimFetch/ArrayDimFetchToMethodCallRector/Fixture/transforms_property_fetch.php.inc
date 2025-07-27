<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

use Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Source\SomeExtendedClass;

class FooBar extends SomeExtendedClass
{
    public function someMethod()
    {
        $this->something['key'];
        $this->something['key'] = 42;
        isset($this->something['key']);
        unset($this->something['key']);
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
        $this->something->get('key');
        $this->something->set('key', 42);
        $this->something->has('key');
        $this->something->unset('key');
    }
}

?>
