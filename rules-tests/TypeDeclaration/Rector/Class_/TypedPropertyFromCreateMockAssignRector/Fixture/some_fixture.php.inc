<?php

namespace Utils\Rector\Tests\Rector\TypedPropertyFromAssignedMockRector\Fixture;

use PHPUnit\Framework\TestCase;
use Utils\Rector\Tests\Rector\TypedPropertyFromAssignedMockRector\Source\SomeMockedClass;

class SomeTest extends TestCase
{
    public $someMock;

    protected function setUp(): void
    {
        $this->someMock = $this->createMock(SomeMockedClass::class);
    }
}

?>
-----
<?php

namespace Utils\Rector\Tests\Rector\TypedPropertyFromAssignedMockRector\Fixture;

use PHPUnit\Framework\TestCase;
use Utils\Rector\Tests\Rector\TypedPropertyFromAssignedMockRector\Source\SomeMockedClass;

class SomeTest extends TestCase
{
    public \PHPUnit\Framework\MockObject\MockObject $someMock;

    protected function setUp(): void
    {
        $this->someMock = $this->createMock(SomeMockedClass::class);
    }
}

?>
