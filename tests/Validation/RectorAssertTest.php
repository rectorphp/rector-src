<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Validation;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Rector\Core\Validation\RectorAssert;
use Webmozart\Assert\InvalidArgumentException;

final class RectorAssertTest extends TestCase
{
    #[DataProvider('provideDataValidClassNames')]
    #[DoesNotPerformAssertions]
    public function testValidClasNames(string $className): void
    {
        RectorAssert::className($className);
    }

    #[DataProvider('provideDataInvalidClassNames')]
    public function testInvalidClasNames(string $className): void
    {
        $this->expectException(InvalidArgumentException::class);
        RectorAssert::className($className);
    }

    /**
     * @return Iterator<string[]>
     */
    public static function provideDataValidClassNames(): Iterator
    {
        yield ['App'];
        yield ['App\\SomeClass'];
    }

    /**
     * @return Iterator<string[]>
     */
    public static function provideDataInvalidClassNames(): Iterator
    {
        yield ['App Some'];
        yield ['App$SomeClass'];
        yield ['$SomeClass'];
        yield ['App\\\\Some'];
        yield ['3AppSome'];
    }

    #[DataProvider('provideDataValidFunctionNames')]
    #[DoesNotPerformAssertions]
    public function testValidFunctionName(string $functionName): void
    {
        RectorAssert::functionName($functionName);
    }

    /**
     * @return Iterator<string[]>
     */
    public static function provideDataValidFunctionNames(): Iterator
    {
        yield ['some_function'];
        yield ['Namespace\\some_function'];
        yield ['Namespace\\so3me_f6n'];
    }

    #[DataProvider('provideDataValidMehtodNames')]
    #[DoesNotPerformAssertions]
    public function testValidMethodName(string $methodName): void
    {
        RectorAssert::methodName($methodName);
    }

    /**
     * @return Iterator<string[]>
     */
    public static function provideDataValidMehtodNames(): Iterator
    {
        yield ['some_method'];
        yield ['__method_magic'];
        yield ['__M3th0d'];
    }

    #[DataProvider('provideDataInvalidFunctionNames')]
    public function testInvalidFunctionName(string $functionName): void
    {
        $this->expectException(InvalidArgumentException::class);
        RectorAssert::functionName($functionName);
    }

    public static function provideDataInvalidFunctionNames(): Iterator
    {
        yield ['35'];
        yield ['/function'];
        yield ['$function'];
        yield ['-key_name'];
    }

    #[DataProvider('provideDataInvalidNamespaceNames')]
    public function testNamespaceName(string $namespaceName): void
    {
        $this->expectException(InvalidArgumentException::class);
        RectorAssert::namespaceName($namespaceName);
    }

    /**
     * @return Iterator<string[]>
     */
    public static function provideDataInvalidNamespaceNames(): Iterator
    {
        yield ['321Namespace'];
        yield ['$__Namespace'];
        yield ['Name*space'];
    }
}
