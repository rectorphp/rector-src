<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class WithParamsAndReturn extends SomeParentCommand
{
    public function process(int $value): int
    {
        return parent::process($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class WithParamsAndReturn extends SomeParentCommand
{
}

?>
