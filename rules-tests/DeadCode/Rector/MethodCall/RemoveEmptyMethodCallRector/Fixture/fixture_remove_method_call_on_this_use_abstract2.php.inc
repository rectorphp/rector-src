<?php

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector\Fixture;

final class GeneratorStubUseAbstract2 extends Validator2
{
    public function __construct()
    {
        $this->validateLineLengths();
    }
}

abstract class Validator2
{
    protected function validateLineLengths(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector\Fixture;

final class GeneratorStubUseAbstract2 extends Validator2
{
    public function __construct()
    {
    }
}

abstract class Validator2
{
    protected function validateLineLengths(): void
    {
    }
}

?>
