<?php

declare(strict_types=1);

namespace Rector\Tests\NodeManipulator;

use PhpParser\Node\Const_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\PrettyPrinter\Standard;
use PHPStan\Type\ObjectType;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ClassDependencyManipulatorTest extends AbstractLazyTestCase
{
    private ClassDependencyManipulator $classDependencyManipulator;

    private Standard $printerStandard;

    protected function setUp(): void
    {
        $this->classDependencyManipulator = $this->make(ClassDependencyManipulator::class);
        $this->printerStandard = new Standard();
    }

    public function testEmptyClass(): void
    {
        $someClass = new Class_(new Identifier('EmptyClass'));

        $this->addSingleDependency($someClass);
        $this->asssertClassEqualsFile($someClass, __DIR__ . '/Fixture/expected_empty_class.php.inc');
    }

    public function testSingleMethod(): void
    {
        $someClass = new Class_(new Identifier('SingleMethodClass'));
        $someClass->stmts[] = new ClassMethod('firstMethod');

        $this->addSingleDependency($someClass);

        $this->asssertClassEqualsFile($someClass, __DIR__ . '/Fixture/expected_single_method.php.inc');
    }

    public function testWithProperty(): void
    {
        $someClass = new Class_(new Identifier('ClassWithSingleProperty'));
        $someClass->stmts[] = new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('someProperty')]);

        $this->addSingleDependency($someClass);

        $this->asssertClassEqualsFile($someClass, __DIR__ . '/Fixture/expected_single_property.php.inc');

    }

    public function testWithMethodAndProperty(): void
    {
        $someClass = new Class_(new Identifier('ClassWithMethodAndProperty'));
        $someClass->stmts[] = new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('someProperty')]);
        $someClass->stmts[] = new ClassMethod(new Identifier('someMethod'));

        $this->addSingleDependency($someClass);

        $this->asssertClassEqualsFile($someClass, __DIR__ . '/Fixture/expected_method_and_property.php.inc');
    }

    public function testConstantProperties(): void
    {
        $someClass = new Class_(new Identifier('ConstantProperties'));
        $someClass->stmts[] = new ClassConst([new Const_('SOME_CONST', new String_('value'))]);
        $someClass->stmts[] = new Property(Class_::MODIFIER_PUBLIC, [new PropertyProperty('someProperty')]);
        $someClass->stmts[] = new Property(Class_::MODIFIER_PUBLIC, [new PropertyProperty('anotherProperty')]);

        $this->addSingleDependency($someClass);

        $this->asssertClassEqualsFile($someClass, __DIR__ . '/Fixture/expected_class_const_property.php.inc');
    }

    private function addSingleDependency(Class_ $class): void
    {
        $this->classDependencyManipulator->addConstructorDependency($class, new PropertyMetadata(
            'eventDispatcher',
            new ObjectType('EventDispatcherInterface')
        ));
    }

    private function asssertClassEqualsFile(Class_ $class, string $expectedFilePath): void
    {
        $printedClass = $this->printerStandard->prettyPrintFile([$class]);

        // normalize newline in Windows
        $printedClass = str_replace("\n", PHP_EOL, $printedClass . "\n");

        $this->assertStringEqualsFile($expectedFilePath, $printedClass);
    }
}
