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

        $expectedString = file_get_contents($expectedFile);
        $expectedString = self::normalize($expectedString);
        $constraint = new IsEqual($expectedString);

        $actualString = self::normalize($actualString);

        parent::assertThat($actualString, $constraint, $message);
    }

    private static function normalize(string $string) {
        $string = str_replace("\r\n", "\n", $string);
        $string = str_replace(DIRECTORY_SEPARATOR, "/", $string);

        return $string;
    }
}
