<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

use Exception;
use PHPUnit\Framework\TestCase;

final class SkipTestMethod extends TestCase
{
    public function testSomething(): void
    {
        $this->expectException(Exception::class);

        throw new \InvalidArgumentException();
    }
}
