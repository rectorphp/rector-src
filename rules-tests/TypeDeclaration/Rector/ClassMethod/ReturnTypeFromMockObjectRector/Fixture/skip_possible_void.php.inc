<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromMockObjectRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipPossibleVoid extends TestCase
{
    public function test()
    {
        if (rand(0, 1)) {
            return $this->createMock('SomeType');
        }
    }
}