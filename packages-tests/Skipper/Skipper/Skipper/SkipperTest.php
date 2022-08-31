<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper\Skipper;

use Iterator;
use PHPUnit\Framework\TestCase;
use Rector\Core\Kernel\RectorKernel;
use Rector\Skipper\Skipper\Skipper;
use Rector\Tests\Skipper\Skipper\Skipper\Fixture\Element\FifthElement;
use Rector\Tests\Skipper\Skipper\Skipper\Fixture\Element\SixthSense;
use Rector\Tests\Skipper\Skipper\Skipper\Fixture\Element\ThreeMan;
use Symplify\SmartFileSystem\SmartFileInfo;

final class SkipperTest extends TestCase
{
    private Skipper $skipper;

    protected function setUp(): void
    {
        $rectorKernel = new RectorKernel();
        $container = $rectorKernel->createFromConfigs([__DIR__ . '/config/config.php']);

        $this->skipper = $container->get(Skipper::class);
    }

    /**
     * @dataProvider provideDataShouldSkipFileInfo()
     */
    public function testSkipFileInfo(string $filePath, bool $expectedSkip): void
    {
        $smartFileInfo = new SmartFileInfo($filePath);

        $resultSkip = $this->skipper->shouldSkipFileInfo($smartFileInfo);
        $this->assertSame($expectedSkip, $resultSkip);
    }

    /**
     * @return Iterator<string[]|bool[]>
     */
    public function provideDataShouldSkipFileInfo(): Iterator
    {
        yield [__DIR__ . '/Fixture/SomeRandom/file.txt', false];
        yield [__DIR__ . '/Fixture/SomeSkipped/any.txt', true];
    }

    /**
     * @param object|class-string $element
     * @dataProvider provideDataShouldSkipElement()
     */
    public function testSkipElement(string|object $element, bool $expectedSkip): void
    {
        $resultSkip = $this->skipper->shouldSkipElement($element);
        $this->assertSame($expectedSkip, $resultSkip);
    }

    /**
     * @return Iterator<bool[]|class-string<SixthSense>[]|class-string<ThreeMan>[]|FifthElement[]>
     */
    public function provideDataShouldSkipElement(): Iterator
    {
        yield [ThreeMan::class, false];
        yield [SixthSense::class, true];
        yield [new FifthElement(), true];
    }
}
