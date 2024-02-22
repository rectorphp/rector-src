<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;

final class IncludeMockDoc extends TestCase
{
    /** @var \stdClass */
    private $value;

    public function setUp(): void
    {
        parent::setUp();

        $this->value = $this->createMock(\DateTime::class);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;

final class IncludeMockDoc extends TestCase
{
    /** @var (\PHPUnit\Framework\MockObject\MockObject & \stdClass) */
    private \PHPUnit\Framework\MockObject\MockObject $value;

    public function setUp(): void
    {
        parent::setUp();

        $this->value = $this->createMock(\DateTime::class);
    }
}

?>
