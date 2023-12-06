<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;

final class IncludeMock extends TestCase
{
    private $value;

    public function setUp(): void
    {
        parent::setUp();

        $this->value = $this->createMock(\stdClass::class);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;

final class IncludeMock extends TestCase
{
    private \PHPUnit\Framework\MockObject\MockObject $value;

    public function setUp(): void
    {
        parent::setUp();

        $this->value = $this->createMock(\stdClass::class);
    }
}

?>
