<?php

namespace Rector\Testing\PHPUnit;

use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Relaxes phpunit assertions to be forgiving about platform issues, like directory-separators or newlines.
 */
trait PlatformAgnosticAssertions {
    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference
     * the same object.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-template ExpectedType
     * @psalm-param ExpectedType $expected
     * @psalm-assert =ExpectedType $actual
     */
    public static function assertSame($expected, $actual, string $message = ''): void
    {
        if (is_string($expected)) {
            $expected = self::normalize($expected);
        }

        if (is_string($actual)) {
            $actual = self::normalize($actual);
        }

        parent::assertSame($expected, $actual, $message);
    }

    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringEqualsFile(string $expectedFile, string $actualString, string $message = ''): void
    {
        parent::assertFileExists($expectedFile, $message);

        $expectedString = self::getNormalizedFileContents($expectedFile);
        $constraint = new IsEqual($expectedString);

        $actualString = self::normalize($actualString);

        parent::assertThat($actualString, $constraint, $message);
    }

    /**
     * Asserts that the contents of one file is equal to the contents of another
     * file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileEquals(string $expected, string $actual, string $message = ''): void
    {
        static::assertFileExists($expected, $message);
        static::assertFileExists($actual, $message);

        $constraint = new IsEqual(self::getNormalizedFileContents($expected));

        static::assertThat(self::getNormalizedFileContents($actual), $constraint, $message);
    }

    private static function normalize(string $string) {
        $string = str_replace("\r\n", "\n", $string);
        $string = str_replace(DIRECTORY_SEPARATOR, "/", $string);

        return $string;
    }

    private static function getNormalizedFileContents(string $filePath): string {
        $expectedString = file_get_contents($filePath);
        return self::normalize($expectedString);
    }
}
