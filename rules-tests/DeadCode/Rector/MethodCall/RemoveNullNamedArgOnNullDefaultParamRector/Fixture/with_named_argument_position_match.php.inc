<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullNamedArgOnNullDefaultParamRector\Fixture;

use Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullNamedArgOnNullDefaultParamRector\Source\SomeExternalClass;

final class WithNamedArgumentPositionMatch
{
    public function get()
    {
        $object = new SomeExternalClass(name: 'John', id: null);

        $secondObject = new SomeExternalClass(id: null, name: 'John');
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullNamedArgOnNullDefaultParamRector\Fixture;

use Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullNamedArgOnNullDefaultParamRector\Source\SomeExternalClass;

final class WithNamedArgumentPositionMatch
{
    public function get()
    {
        $object = new SomeExternalClass(name: 'John');

        $secondObject = new SomeExternalClass(name: 'John');
    }
}

?>
