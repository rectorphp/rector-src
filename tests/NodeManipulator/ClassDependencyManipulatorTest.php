<?php

declare(strict_types=1);

namespace Rector\Tests\NodeManipulator;

use PhpParser\Modifiers;
use PhpParser\Node\Const_;
use PhpParser\Node\Identifier;
use PhpParser\Node\PropertyItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\PrettyPrinter\Standard;
use PHPStan\Type\ObjectType;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\PhpVersionFeature;

final class ClassDependencyManipulatorTest extends AbstractLazyTestCase
{
    private ClassDependencyManipulator $classDependencyManipulator;

    private Standard $printerStandard;

    protected function setUp(): void
    {
        $this->classDependencyManipulator = $this->make(ClassDependencyManipulator::class);

        $this->printerStandard = new Standard();

        // use at least readonly property
        SimpleParameterProvider::setParameter(Option::PHP_VERSION_FEATURES, PhpVersionFeature::READONLY_PROPERTY);
    }

    public function testEmptyClass(): void
    {
        $someClass = new Class_(new Identifier('EmptyClass'));

        $this->setNamespacedName($someClass);

        $this->addSingleDependency($someClass);
        $this->asssertClassEqualsFile($someClass, __DIR__ . '/Fixture/expected_empty_class.php.inc');
    }

    public function testSingleMethod(): void
    {
        $someClass = new Class_(new Identifier('SingleMethodClass'));
        $this->setNamespacedName($someClass);

        $someClass->stmts[] = new ClassMethod('firstMethod');

        $this->addSingleDependency($someClass);

        $this->asssertClassEqualsFile($someClass, __DIR__ . '/Fixture/expected_single_method.php.inc');
    }

    public function testWithProperty(): void
    {
        $someClass = new Class_(new Identifier('ClassWithSingleProperty'));

        $this->setNamespacedName($someClass);

        $someClass->stmts[] = new Property(Modifiers::PRIVATE, [new PropertyItem('someProperty')]);

        $this->addSingleDependency($someClass);

        $this->asssertClassEqualsFile($someClass, __DIR__ . '/Fixture/expected_single_property.php.inc');

    }

    public function testWithMethodAndProperty(): void
    {
        $someClass = new Class_(new Identifier('ClassWithMethodAndProperty'));

        $this->setNamespacedName($someClass);

        $someClass->stmts[] = new Property(Modifiers::PRIVATE, [new PropertyItem('someProperty')]);
        $someClass->stmts[] = new ClassMethod(new Identifier('someMethod'));

        $this->addSingleDependency($someClass);

        $this->asssertClassEqualsFile($someClass, __DIR__ . '/Fixture/expected_method_and_property.php.inc');
    }

    public function testConstantProperties(): void
    {
        $someClass = new Class_(new Identifier('ConstantProperties'));

        $this->setNamespacedName($someClass);

        $someClass->stmts[] = new ClassConst([new Const_('SOME_CONST', new String_('value'))]);
        $someClass->stmts[] = new Property(Modifiers::PUBLIC, [new PropertyItem('someProperty')]);
        $someClass->stmts[] = new Property(Modifiers::PUBLIC, [new PropertyItem('anotherProperty')]);

        $this->addSingleDependency($someClass);

        $this->asssertClassEqualsFile($someClass, __DIR__ . '/Fixture/expected_class_const_property.php.inc');
    }

    private function setNamespacedName(Class_ $class): void
    {
        $nameResolver = new NameResolver();
        $nodeTraverser = new NodeTraverser($nameResolver);

        $nodeTraverser->traverse([$class]);
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
