<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Contract\Rector\RectorInterface;
use Rector\Skipper\Skipper\Skipper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Skipper\Skipper\Fixture\Element\FifthElement;
use Rector\Tests\Skipper\Skipper\Source\AnotherClassToSkip;

final class SkipperTest extends AbstractLazyTestCase
{
    private Skipper $skipper;

    protected function setUp(): void
    {
        parent::setUp();

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

    #[DataProvider('provideRectorAndFile')]
    public function testSkipElementAndFilePath(RectorInterface $rector, string $filePath, bool $expectedSkip): void
    {
        $resolvedSkip = $this->skipper->shouldSkipRectorAndFile($rector, $filePath);
        $this->assertSame($expectedSkip, $resolvedSkip);
    }

    public static function provideRectorAndFile(): Iterator
    {
        yield [new FifthElement(), __DIR__ . '/Fixture', true];

        yield [new AnotherClassToSkip(), __DIR__ . '/Fixture/someFile', true];
        yield [new AnotherClassToSkip(), __DIR__ . '/Fixture/someDirectory/anotherFile.php', true];
    }
}
