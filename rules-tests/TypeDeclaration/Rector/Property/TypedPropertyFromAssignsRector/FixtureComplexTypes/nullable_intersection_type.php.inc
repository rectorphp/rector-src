<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NullableIntersectionType extends TestCase
{
    /**
     * @var DateTime&MockObject
     */
    private $property;

    public function testExample(): void
    {
        $this->property = $this->createMock(DateTime::class);
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

class NullableIntersectionType extends TestCase
{
    /**
     * @var DateTime&MockObject
     */
    private ?\PHPUnit\Framework\MockObject\MockObject $property = null;

    public function testExample(): void
    {
        $this->property = $this->createMock(DateTime::class);
        $this->property->expects(self::once())->method('format');
        $this->property->format('Y');
    }
}

?>
