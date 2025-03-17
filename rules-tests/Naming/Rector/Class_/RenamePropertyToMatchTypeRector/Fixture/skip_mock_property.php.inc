<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\EliteManager;

final class SkipMockProperty extends TestCase
{
    private MockObject $eliteManagerMock;

    public function test()
    {
        $this->eliteManagerMock = $this->createMock(EliteManager::class);
    }
}
