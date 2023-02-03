<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Util\Reflection;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Core\Tests\Util\Reflection\Fixture\SomeClassWithPrivateMethods;
use Rector\Core\Tests\Util\Reflection\Fixture\SomeClassWithPrivateProperty;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use stdClass;

final class PrivatesAccessorTest extends TestCase
{
    private readonly PrivatesAccessor $privatesAccessor;

    protected function setUp(): void
    {
        $this->privatesAccessor = new PrivatesAccessor();
    }

    /**
     * @param class-string<SomeClassWithPrivateMethods>|SomeClassWithPrivateMethods $object
     * @param mixed[]|int[] $arguments
     */
    #[DataProvider('provideData()')]
    public function test(
        string | SomeClassWithPrivateMethods $object,
        string $methodName,
        array $arguments,
        int $expectedResult
    ): void {
        $result = $this->privatesAccessor->callPrivateMethod($object, $methodName, $arguments);
        $this->assertSame($expectedResult, $result);
    }

    public static function provideData(): Iterator
    {
        yield [SomeClassWithPrivateMethods::class, 'getNumber', [], 5];
        yield [new SomeClassWithPrivateMethods(), 'getNumber', [], 5];
        yield [new SomeClassWithPrivateMethods(), 'plus10', [30], 40];
    }

    public function testGetterSetter(): void
    {
        $privatesAccessor = new PrivatesAccessor();
        $someClassWithPrivateProperty = new SomeClassWithPrivateProperty();

        $fetchedValue = $privatesAccessor->getPrivateProperty($someClassWithPrivateProperty, 'value');
        $this->assertSame($someClassWithPrivateProperty->getValue(), $fetchedValue);

        $fetchedParentValue = $privatesAccessor->getPrivateProperty($someClassWithPrivateProperty, 'parentValue');
        $this->assertSame($someClassWithPrivateProperty->getParentValue(), $fetchedParentValue);

        $privatesAccessor->setPrivateProperty($someClassWithPrivateProperty, 'value', 25);
        $this->assertSame(25, $someClassWithPrivateProperty->getValue());
    }

    public function testGetterSetterTypesafe(): void
    {
        $privatesAccessor = new PrivatesAccessor();
        $someClassWithPrivateProperty = new SomeClassWithPrivateProperty();

        $newObject = new stdClass();
        $this->assertNotSame($newObject, $someClassWithPrivateProperty->getObject());
        $privatesAccessor->setPrivatePropertyOfClass(
            $someClassWithPrivateProperty,
            'object',
            $newObject,
            stdClass::class
        );
        $this->assertSame($newObject, $someClassWithPrivateProperty->getObject());

        $fetchedValue = $privatesAccessor->getPrivatePropertyOfClass(
            $someClassWithPrivateProperty,
            'object',
            stdClass::class
        );
        $this->assertSame($someClassWithPrivateProperty->getObject(), $fetchedValue);
    }
}
