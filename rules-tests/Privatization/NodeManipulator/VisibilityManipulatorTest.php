<?php

declare(strict_types=1);

namespace Rector\Tests\Privatization\NodeManipulator;

use PhpParser\Node\Stmt\ClassMethod;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\Visibility;

final class VisibilityManipulatorTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        $visibilityManipulator = $this->make(VisibilityManipulator::class);

        $classMethod = new ClassMethod('SomeClass');
        $classMethod->flags = Visibility::PUBLIC | Visibility::STATIC;

        $visibilityManipulator->changeNodeVisibility($classMethod, Visibility::PROTECTED);
        $this->assertSame(Visibility::PROTECTED | Visibility::STATIC, $classMethod->flags);
    }
}
