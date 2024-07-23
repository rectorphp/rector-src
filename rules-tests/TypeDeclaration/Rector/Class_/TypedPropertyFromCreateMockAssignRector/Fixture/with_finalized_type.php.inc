<?php

namespace Utils\Rector\Tests\Rector\TypedPropertyFromAssignedMockRector\Fixture;

use PHPUnit\Framework\TestCase;
use Utils\Rector\Tests\Rector\TypedPropertyFromAssignedMockRector\Source\SomeFinalizedMockedClass;
use Utils\Rector\Tests\Rector\TypedPropertyFromAssignedMockRector\Source\SomeMockedClass;

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

namespace Utils\Rector\Tests\Rector\TypedPropertyFromAssignedMockRector\Fixture;

use PHPUnit\Framework\TestCase;
use Utils\Rector\Tests\Rector\TypedPropertyFromAssignedMockRector\Source\SomeFinalizedMockedClass;
use Utils\Rector\Tests\Rector\TypedPropertyFromAssignedMockRector\Source\SomeMockedClass;

class WithFinalizedType extends TestCase
{
    public \PHPUnit\Framework\MockObject\MockObject $someMock;

    protected function setUp(): void
    {
        $this->someMock = $this->createMock(SomeFinalizedMockedClass::class);
    }
}

?>
