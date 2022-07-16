<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Validation;

use Iterator;
use PHPUnit\Framework\TestCase;
use Rector\Core\Validation\RectorAssert;
use Webmozart\Assert\InvalidArgumentException;

final class RectorAssertTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     * @dataProvider provideDataValidClassNames()
     */
    public function testValidClasNames(string $className): void
    {
        RectorAssert::className($className);
    }

    /**
     * @dataProvider provideDataInvalidClassNames()
     */
    public function testInvalidClasNames(string $className): void
    {
        $this->expectException(InvalidArgumentException::class);
        RectorAssert::className($className);
    }

    /**
     * @return Iterator<string[]>
     */
    public function provideDataValidClassNames(): Iterator
    {
        yield ['App'];
        yield ['App\\SomeClass'];
    }

    /**
     * @return Iterator<string[]>
     */
    public function provideDataInvalidClassNames(): Iterator
    {
        yield ['App Some'];
        yield ['App$SomeClass'];
        yield ['$SomeClass'];
        yield ['App\\\\Some'];
        yield ['3AppSome'];
    }

    /**
     * @doesNotPerformAssertions
     * @dataProvider provideDataValidFunctionNames()
     */
    public function testValidFunctionName(string $functionName): void
    {
        RectorAssert::functionName($functionName);
    }

    /**
     * @return \Iterator<string[]>
     */
    public function provideDataValidFunctionNames(): Iterator
    {
        yield ['some_function'];
        yield ['Namespace\\some_function'];
        yield ['Namespace\\so3me_f6n'];
    }

    /**
     * @doesNotPerformAssertions
     * @dataProvider provideDataValidMehtodNames()
     */
    public function testValidMethodName(string $methodName): void
    {
        RectorAssert::methodName($methodName);
    }

    /**
     * @return \Iterator<string[]>
     */
    public function provideDataValidMehtodNames(): Iterator
    {
        yield ['some_method'];
        yield ['__method_magic'];
        yield ['__M3th0d'];
    }
}
