<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Source\SomeFinalizedMockedClass;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Source\SomeMockedClass;

class WithFinalizedType extends TestCase
{
    public $someMock;

    protected function setUp(): void
    {
        $this->someMock = $this->createMock(SomeFinalizedMockedClass::class);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Source\SomeFinalizedMockedClass;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Source\SomeMockedClass;

class WithFinalizedType extends TestCase
{
    public \PHPUnit\Framework\MockObject\MockObject $someMock;

    protected function setUp(): void
    {
        $this->someMock = $this->createMock(SomeFinalizedMockedClass::class);
    }
}

?>
