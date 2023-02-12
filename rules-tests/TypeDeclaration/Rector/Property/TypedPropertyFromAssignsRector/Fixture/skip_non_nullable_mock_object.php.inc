<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipNonNullableMockObject extends TestCase
{
    private $someValue;

    protected function setUp(): void
    {
        $this->someValue = $this->createMock('SomeClass');
    }
}
