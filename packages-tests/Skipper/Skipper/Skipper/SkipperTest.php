<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper\Skipper;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Core\Kernel\RectorKernel;
use Rector\Skipper\Skipper\Skipper;
use Rector\Tests\Skipper\Skipper\Skipper\Fixture\Element\FifthElement;
use Rector\Tests\Skipper\Skipper\Skipper\Fixture\Element\SixthSense;
use Rector\Tests\Skipper\Skipper\Skipper\Fixture\Element\ThreeMan;

final class SkipperTest extends TestCase
{
    private Skipper $skipper;

    protected function setUp(): void
    {
        $rectorKernel = new RectorKernel();
        $containerBuilder = $rectorKernel->createFromConfigs([__DIR__ . '/config/config.php']);

        $this->skipper = $containerBuilder->get(Skipper::class);
    }

    #[DataProvider('provideDataShouldSkipFileInfo')]
    public function testSkipFileInfo(string $filePath, bool $expectedSkip): void
    {
        $filePathResultSkip = $this->skipper->shouldSkipFilePath($filePath);
        $this->assertSame($expectedSkip, $filePathResultSkip);
    }

    /**
     * @return Iterator<string[]|bool[]>
     */
    public static function provideDataShouldSkipFileInfo(): Iterator
    {
        yield [__DIR__ . '/Fixture/SomeRandom/file.txt', false];
        yield [__DIR__ . '/Fixture/SomeSkipped/any.txt', true];
        yield ['packages-tests/Skipper/Skipper/Skipper/Fixture/SomeSkippedPath/any.txt', true];
        yield ['packages-tests/Skipper/Skipper/Skipper/Fixture/SomeSkippedPathToFile/any.txt', true];
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

    /**
     * @return Iterator<bool[]|class-string<SixthSense>[]|class-string<ThreeMan>[]|FifthElement[]>
     */
    public static function provideDataShouldSkipElement(): Iterator
    {
        yield [ThreeMan::class, false];
        yield [SixthSense::class, true];
        yield [new FifthElement(), true];
    }
}
