<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Fixture;

use PHPUnit\Framework\TestCase;

class SkipNonMockObjectType extends TestCase
{
    public $someProperty;

    protected function setUp(): void
    {
        $this->someProperty = 'some property value';
    }
}
