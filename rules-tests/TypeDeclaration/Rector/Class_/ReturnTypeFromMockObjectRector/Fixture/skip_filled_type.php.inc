<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromMockObjectRector\Fixture;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class SkipFilledType extends TestCase
{
    public function createSomeMock(): MockObject
    {
        return $this->createMock('SomeType');
    }
}
