<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

use PHPUnit\Framework\TestCase;

final class CreateStubInTest extends TestCase
{
    public function test($params)
    {
        $user = $this->createStub('SomeClass');
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

use PHPUnit\Framework\TestCase;

final class CreateStubInTest extends TestCase
{
    public function test($params)
    {
    }
}

?>
