<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromMockObjectRector\Fixture;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class SkipReturnByDocblock extends TestCase
{
    public function createSomeMock()
    {
        /** @var MockObject $x */
        return $x;
    }
}