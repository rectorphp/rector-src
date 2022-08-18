<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use PHPUnit\Framework\TestCase;

final class MockPropertyType extends TestCase
{
    private $someValue;

    protected function setUp(): void
    {
        $this->someValue = $this->createMock('SomeClass');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use PHPUnit\Framework\TestCase;

final class MockPropertyType extends TestCase
{
    private \PHPUnit\Framework\MockObject\MockObject $someValue;

    protected function setUp(): void
    {
        $this->someValue = $this->createMock('SomeClass');
    }
}

?>
