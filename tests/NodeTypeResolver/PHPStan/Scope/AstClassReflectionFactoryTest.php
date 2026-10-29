<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\PHPStan\Scope;

use PhpParser\Comment\Doc;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\NodeTypeResolver\PHPStan\Scope\AstClassReflectionFactory;
use Rector\NodeTypeResolver\PHPStan\Scope\ScopeFactory;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class AstClassReflectionFactoryTest extends AbstractLazyTestCase
{
    public function testReuseAndInvalidateReflection(): void
    {
        $factory = $this->make(AstClassReflectionFactory::class);
        $scope = $this->make(ScopeFactory::class)->createFromFile(__FILE__);
        $method = new ClassMethod('run', [
            'stmts' => [],
        ]);
        $class = new Class_('ChangingClass', [
            'stmts' => [$method],
        ], [
            'startLine' => 1,
            'endLine' => 5,
        ]);
        $class->namespacedName = new Name('ChangingClass');

        $reflection = $factory->create($class, $scope);
        $this->assertNotNull($reflection);
        $this->assertSame($reflection, $factory->create($class, $scope));
        $this->assertFalse($reflection->getNativeReflection()->getMethod('run')->isGenerator());

        // Method bodies affect reflection even when the signature stays the same.
        $method->stmts = [new Expression(new Yield_())];
        $generatorReflection = $factory->create($class, $scope);
        $this->assertNotSame($reflection, $generatorReflection);
        $this->assertTrue($generatorReflection->getNativeReflection()->getMethod('run')->isGenerator());

        $method->setDocComment(new Doc('/** @return iterable<int> */'));
        $documentedReflection = $factory->create($class, $scope);
        $this->assertNotSame($generatorReflection, $documentedReflection);
        $this->assertSame('/** @return iterable<int> */', $documentedReflection->getNativeReflection()->getMethod('run')->getDocComment());

        $method->name = new Identifier('renamed');
        $renamedReflection = $factory->create($class, $scope);
        $this->assertFalse($renamedReflection->hasNativeMethod('run'));
        $this->assertTrue($renamedReflection->hasNativeMethod('renamed'));
    }
}
