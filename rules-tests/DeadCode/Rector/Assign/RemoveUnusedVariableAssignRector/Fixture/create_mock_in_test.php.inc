<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

use PHPUnit\Framework\TestCase;

final class CreateMockInTest extends TestCase
{
    public function test($params)
    {
        $tmp = $this->createMock('SomeClass');
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

use PHPUnit\Framework\TestCase;

final class CreateMockInTest extends TestCase
{
    public function test($params)
    {
    }
}

?>
