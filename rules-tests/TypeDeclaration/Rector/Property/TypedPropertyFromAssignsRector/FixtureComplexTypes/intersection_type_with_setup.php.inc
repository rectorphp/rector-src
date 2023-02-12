<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IntersectionTypeWithSetup extends TestCase
{
    /**
     * @var DateTime&MockObject
     */
    private $property;

    public function setUp(): void
    {
        $this->property = $this->createMock(DateTime::class);
    }

    public function testExample(): void
    {
        $this->property->expects(self::once())->method('format');
        $this->property->format('Y');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IntersectionTypeWithSetup extends TestCase
{
    private \DateTime&\PHPUnit\Framework\MockObject\MockObject $property;

    public function setUp(): void
    {
        $this->property = $this->createMock(DateTime::class);
    }

    public function testExample(): void
    {
        $this->property->expects(self::once())->method('format');
        $this->property->format('Y');
    }
}

?>
