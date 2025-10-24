<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

use Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Source\SomeExternalClass;

final class WithNamedArgumentPositionMatch
{
    public function get()
    {
        $object = new SomeExternalClass(name: 'John', id: null);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

use Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Source\SomeExternalClass;

final class WithNamedArgumentPositionMatch
{
    public function get()
    {
        $object = new SomeExternalClass(name: 'John');
    }
}

?>
