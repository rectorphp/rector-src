<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper\Skip;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Skipper\Skipper\Skipper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Skipper\Skipper\Skip\Source\AnotherClassToSkip;
use Rector\Tests\Skipper\Skipper\Skip\Source\NotSkippedClass;
use Rector\Tests\Skipper\Skipper\Skip\Source\SomeClassToSkip;

final class SkipSkipperTest extends AbstractLazyTestCase
{
    private Skipper $skipper;

    protected function setUp(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, [
            // classes
            SomeClassToSkip::class,

            // classes only in specific paths
            AnotherClassToSkip::class => ['Fixture/someFile', '*/someDirectory/*'],

            // file paths
            __DIR__ . '/Fixture/AlwaysSkippedPath',
            '*\PathSkippedWithMask\*',
        ]);

        $this->skipper = $this->make(Skipper::class);
    }

    protected function tearDown(): void
    {
        // null the parameter
        SimpleParameterProvider::setParameter(Option::SKIP, []);
    }

    #[DataProvider('provideCheckerAndFile')]
    #[DataProvider('provideFilePath')]
    public function test(string $element, string $filePath, bool $expectedSkip): void
    {
        $resolvedSkip = $this->skipper->shouldSkipElementAndFilePath($element, $filePath);
        $this->assertSame($expectedSkip, $resolvedSkip);
    }

    public static function provideCheckerAndFile(): Iterator
    {
        yield [SomeClassToSkip::class, __DIR__ . '/Fixture', true];

        yield [AnotherClassToSkip::class, __DIR__ . '/Fixture/someFile', true];
        yield [AnotherClassToSkip::class, __DIR__ . '/Fixture/someDirectory/anotherFile.php', true];
        yield [AnotherClassToSkip::class, __DIR__ . '/Fixture/someDirectory/anotherFile.php', true];

        yield [NotSkippedClass::class, __DIR__ . '/Fixture/someFile', false];
        yield [NotSkippedClass::class, __DIR__ . '/Fixture/someOtherFile', false];
    }

    public static function provideFilePath(): Iterator
    {
        yield ['anything', __DIR__ . '/Fixture/AlwaysSkippedPath/some_file.txt', true];
        yield ['anything', __DIR__ . '/Fixture/PathSkippedWithMask/another_file.txt', true];
    }
}
