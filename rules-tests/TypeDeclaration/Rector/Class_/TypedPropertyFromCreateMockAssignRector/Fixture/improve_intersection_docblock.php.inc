<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Source\SomeMockedClass;

final class ImproveIntersectionDocblock extends TestCase
{
    /**
     * @var SomeMockedClass
     */
    public $someMock;

    protected function setUp(): void
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

final class ClearFalseMock extends TestCase
{
    /**
     * @var SomeMockedClass&\PHPUnit\Framework\MockObject\MockObject
     */
    public \PHPUnit\Framework\MockObject\MockObject $someMock;

    protected function setUp(): void
    {
        $this->someMock = $this->createMock(SomeMockedClass::class);
    }
}

?>
