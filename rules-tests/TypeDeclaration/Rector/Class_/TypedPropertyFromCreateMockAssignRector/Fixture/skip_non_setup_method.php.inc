<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\Source\SomeMockedClass;

class SkipNonSetupMethod extends TestCase
{
    public $someMock;

    protected function factory(): void
    {
        $this->someMock = $this->createMock(SomeMockedClass::class);
    }
}
