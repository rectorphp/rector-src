<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Util\Reflection;

use Iterator;
use PHPUnit\Framework\TestCase;
use Rector\Core\Tests\Util\Reflection\Fixture\SomeClassWithPrivateMethods;
use Rector\Core\Util\Reflection\PrivatesAccessor;

final class PrivatesAccessorTest extends TestCase
{
    private PrivatesAccessor $privatesAccessor;

    protected function setUp(): void
    {
        $this->privatesAccessor = new PrivatesAccessor();
    }

    /**
     * @dataProvider provideData()
     * @param class-string<SomeClassWithPrivateMethods>|SomeClassWithPrivateMethods $object
     * @param mixed[]|int[] $arguments
     */
    public function test(
        string | SomeClassWithPrivateMethods $object,
        string $methodName,
        array $arguments,
        int $expectedResult
    ): void {
        $result = $this->privatesAccessor->callPrivateMethod($object, $methodName, $arguments);
        $this->assertSame($expectedResult, $result);
    }

    public function provideData(): Iterator
    {
        yield [SomeClassWithPrivateMethods::class, 'getNumber', [], 5];
        yield [new SomeClassWithPrivateMethods(), 'getNumber', [], 5];
        yield [new SomeClassWithPrivateMethods(), 'plus10', [30], 40];
    }

    /**
     * @dataProvider provideDataReference()
     */
    public function testReference(
        SomeClassWithPrivateMethods $someClassWithPrivateMethods,
        string $methodName,
        int $referencedArgument,
        int $expectedResult
    ): void {
        $result = $this->privatesAccessor->callPrivateMethodWithReference(
            $someClassWithPrivateMethods,
            $methodName,
            $referencedArgument
        );
        $this->assertSame($expectedResult, $result);
    }

    public function provideDataReference(): Iterator
    {
        yield [new SomeClassWithPrivateMethods(), 'multipleByTwo', 10, 20];
    }
}
