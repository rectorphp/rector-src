<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

use Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Source\SomeExternalClass;

class HandleMiddleDefaultNotNull
{
    public function get(SomeExternalClass $someExternalClass)
    {
        $someExternalClass->withMiddleNotNullDefault(null, 1, null, null);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

use Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Source\SomeExternalClass;

class HandleMiddleDefaultNotNull
{
    public function get(SomeExternalClass $someExternalClass)
    {
        $someExternalClass->withMiddleNotNullDefault(null, 1);
    }
}

?>
