<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use PHPUnit\Framework\TestCase;

final class NonNullableMockObjectWithVar extends TestCase
{
    /**
     * @var DateTime&MockObject
     */
    private $someValue;

    protected function setUp(): void
    {
        $this->someValue = $this->createMock('DateTime');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use PHPUnit\Framework\TestCase;

final class NonNullableMockObjectWithVar extends TestCase
{
    /**
     * @var DateTime&MockObject
     */
    private \PHPUnit\Framework\MockObject\MockObject $someValue;

    protected function setUp(): void
    {
        $this->someValue = $this->createMock('DateTime');
    }
}

?>
