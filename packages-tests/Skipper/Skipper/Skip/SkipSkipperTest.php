<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper\Skip;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Core\Kernel\RectorKernel;
use Rector\Skipper\Skipper\Skipper;
use Rector\Tests\Skipper\Skipper\Skip\Source\AnotherClassToSkip;
use Rector\Tests\Skipper\Skipper\Skip\Source\NotSkippedClass;
use Rector\Tests\Skipper\Skipper\Skip\Source\SomeClassToSkip;

final class SkipSkipperTest extends TestCase
{
    private Skipper $skipper;

    protected function setUp(): void
    {
        $rectorKernel = new RectorKernel();
        $containerBuilder = $rectorKernel->createFromConfigs([__DIR__ . '/config/config.php']);

        $this->skipper = $containerBuilder->get(Skipper::class);
    }

    #[DataProvider('provideCheckerAndFile()')]
    #[DataProvider('provideAnythingAndFilePath()')]
    public function test(string $element, string $filePath, bool $expectedSkip): void
    {
        $resolvedSkip = $this->skipper->shouldSkipElementAndFilePath($element, $filePath);
        $this->assertSame($expectedSkip, $resolvedSkip);
    }

    /**
     * @return Iterator<string[]|bool[]|class-string<AnotherClassToSkip>[]|class-string<NotSkippedClass>[]|class-string<SomeClassToSkip>[]>
     */
    public static function provideCheckerAndFile(): Iterator
    {
        yield [SomeClassToSkip::class, __DIR__ . '/Fixture', true];

        yield [AnotherClassToSkip::class, __DIR__ . '/Fixture/someFile', true];
        yield [AnotherClassToSkip::class, __DIR__ . '/Fixture/someDirectory/anotherFile.php', true];
        yield [AnotherClassToSkip::class, __DIR__ . '/Fixture/someDirectory/anotherFile.php', true];

        yield [NotSkippedClass::class, __DIR__ . '/Fixture/someFile', false];
        yield [NotSkippedClass::class, __DIR__ . '/Fixture/someOtherFile', false];
    }

    /**
     * @return Iterator<string[]|bool[]>
     */
    public static function provideAnythingAndFilePath(): Iterator
    {
        yield ['anything', __DIR__ . '/Fixture/AlwaysSkippedPath/some_file.txt', true];
        yield ['anything', __DIR__ . '/Fixture/PathSkippedWithMask/another_file.txt', true];
    }
}
