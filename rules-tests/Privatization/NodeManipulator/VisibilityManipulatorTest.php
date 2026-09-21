<?php

declare(strict_types=1);

namespace Rector\Tests\Privatization\NodeManipulator;

use PhpParser\Node\Stmt\ClassMethod;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\MethodName;
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

    public function testMakePrivateRemovesFinal(): void
    {
        $visibilityManipulator = $this->make(VisibilityManipulator::class);

        $classMethod = new ClassMethod('run');
        $classMethod->flags = Visibility::PROTECTED | Visibility::FINAL;

        $visibilityManipulator->makePrivate($classMethod);
        $this->assertSame(Visibility::PRIVATE, $classMethod->flags);
    }

    public function testMakePrivateKeepsFinalOnConstructor(): void
    {
        $visibilityManipulator = $this->make(VisibilityManipulator::class);

        $classMethod = new ClassMethod(MethodName::CONSTRUCT);
        $classMethod->flags = Visibility::PROTECTED | Visibility::FINAL;

        $visibilityManipulator->makePrivate($classMethod);
        $this->assertSame(Visibility::PRIVATE | Visibility::FINAL, $classMethod->flags);
    }
}
