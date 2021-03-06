<?php

declare(strict_types=1);

namespace Rector\Tests\Privatization\NodeManipulator;

use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\ValueObject\Visibility;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class VisibilityManipulatorTest extends AbstractTestCase
{
    private VisibilityManipulator $visibilityManipulator;

    protected function setUp(): void
    {
        $this->boot();
        $this->visibilityManipulator = $this->getService(VisibilityManipulator::class);
    }

    public function test(): void
    {
        $classMethod = new ClassMethod('SomeClass');
        $classMethod->flags = Visibility::PUBLIC | Visibility::STATIC;
        $this->visibilityManipulator->changeNodeVisibility($classMethod, Visibility::PROTECTED);
        $this->assertSame(Visibility::PROTECTED | Visibility::STATIC, $classMethod->flags);
    }
}
