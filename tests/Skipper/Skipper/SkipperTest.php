<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Skipper\Skipper\Skipper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Skipper\Skipper\Fixture\Element\FifthElement;
use Rector\Tests\Skipper\Skipper\Fixture\Element\ThreeMan;
use Rector\Tests\Skipper\Skipper\Source\AnotherClassToSkip;
use Rector\Tests\Skipper\Skipper\Source\NotSkippedClass;

final class SkipperTest extends AbstractLazyTestCase
{
    private Skipper $skipper;

    protected function setUp(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, [
            // windows like path
            '*\SomeSkipped\*',

            // file paths
            __DIR__ . '/Fixture/AlwaysSkippedPath',
            '*\PathSkippedWithMask\*',
            __DIR__ . '/Fixture/SomeSkippedPath',
            __DIR__ . '/Fixture/SomeSkippedPathToFile/any.txt',

            // elements
            FifthElement::class,

            // classes only in specific paths
            AnotherClassToSkip::class => ['Fixture/someFile', '*/someDirectory/*'],
        ]);

        $this->skipper = $this->make(Skipper::class);
    }

    protected function tearDown(): void
    {
        // cleanup configuration
        SimpleParameterProvider::setParameter(Option::SKIP, []);
    }

    #[DataProvider('provideDataShouldSkipFilePath')]
    public function testSkipFilePath(string $filePath, bool $expectedSkip): void
    {
        $filePathResultSkip = $this->skipper->shouldSkipFilePath($filePath);
        $this->assertSame($expectedSkip, $filePathResultSkip);
    }

    /**
     * @return Iterator<string[]|bool[]>
     */
    public static function provideDataShouldSkipFilePath(): Iterator
    {
        yield [__DIR__ . '/Fixture/SomeRandom/file.txt', false];
        yield [__DIR__ . '/Fixture/SomeSkipped/any.txt', true];
        yield ['tests/Skipper/Skipper/Fixture/SomeSkippedPath/any.txt', true];
        yield ['tests/Skipper/Skipper/Fixture/SomeSkippedPathToFile/any.txt', true];
        yield [__DIR__ . '/Fixture/AlwaysSkippedPath/some_file.txt', true];
        yield [__DIR__ . '/Fixture/PathSkippedWithMask/another_file.txt', true];
    }

    /**
     * @param object|class-string $element
     */
    #[DataProvider('provideDataShouldSkipElement')]
    public function testSkipElement(string|object $element, bool $expectedSkip): void
    {
        $resultSkip = $this->skipper->shouldSkipElement($element);
        $this->assertSame($expectedSkip, $resultSkip);
    }

    #[DataProvider('provideCheckerAndFile')]
    public function testSkipElementAndFilePath(string $element, string $filePath, bool $expectedSkip): void
    {
        $resolvedSkip = $this->skipper->shouldSkipElementAndFilePath($element, $filePath);
        $this->assertSame($expectedSkip, $resolvedSkip);
    }

    public static function provideCheckerAndFile(): Iterator
    {
        yield [FifthElement::class, __DIR__ . '/Fixture', true];

        yield [AnotherClassToSkip::class, __DIR__ . '/Fixture/someFile', true];
        yield [AnotherClassToSkip::class, __DIR__ . '/Fixture/someDirectory/anotherFile.php', true];

        yield [NotSkippedClass::class, __DIR__ . '/Fixture/someFile', false];
        yield [NotSkippedClass::class, __DIR__ . '/Fixture/someOtherFile', false];
    }

    public static function provideDataShouldSkipElement(): Iterator
    {
        yield [ThreeMan::class, false];
        yield [FifthElement::class, true];
        yield [new FifthElement(), true];
    }
}
