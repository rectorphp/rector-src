<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Source\SomeMockedClass;

class NullableMockObject extends TestCase
{
    public $someMock;

    protected function setUp(): void
    {
        $this->someMock = $this->createMock(SomeMockedClass::class);
    }

    protected function tearDown(): void
    {
        $this->someMock = null;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Source\SomeMockedClass;

class NullableMockObject extends TestCase
{
    public ?\PHPUnit\Framework\MockObject\MockObject $someMock;

    protected function setUp(): void
    {
        $this->someMock = $this->createMock(SomeMockedClass::class);
    }

    protected function tearDown(): void
    {
        $this->someMock = null;
    }
}

?>