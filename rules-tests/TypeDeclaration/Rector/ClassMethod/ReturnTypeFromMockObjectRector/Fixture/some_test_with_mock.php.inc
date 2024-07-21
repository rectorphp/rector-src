<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromMockObjectRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SomeTestWithMock extends TestCase
{
    public function test()
    {
        return $this->createMock('SomeType');
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromMockObjectRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SomeTestWithMock extends TestCase
{
    public function test(): \PHPUnit\Framework\MockObject\MockObject
    {
        return $this->createMock('SomeType');
    }
}

?>
