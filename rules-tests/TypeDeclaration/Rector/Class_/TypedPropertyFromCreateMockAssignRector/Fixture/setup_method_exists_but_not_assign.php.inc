<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Source\SomeMockedClass;

class SetupMethodExistsButNotAssign extends TestCase
{
    public $someMock;

    protected function setUp(): void
    {
    }

    private function factory()
    {
        $this->someMock = $this->createMock(SomeMockedClass::class);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Source\SomeMockedClass;

class SetupMethodExistsButNotAssign extends TestCase
{
    public ?\PHPUnit\Framework\MockObject\MockObject $someMock = null;

    protected function setUp(): void
    {
    }

    private function factory()
    {
        $this->someMock = $this->createMock(SomeMockedClass::class);
    }
}

?>
