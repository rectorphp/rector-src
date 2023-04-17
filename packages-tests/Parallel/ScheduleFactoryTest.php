<?php

declare(strict_types=1);

namespace Rector\Tests\Parallel;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Core\Kernel\RectorKernel;
use Rector\Parallel\ScheduleFactory;

final class ScheduleFactoryTest extends TestCase
{
    private ScheduleFactory $scheduleFactory;

    protected function setUp(): void
    {
        $rectorKernel = new RectorKernel();
        $container = $rectorKernel->create();

        $this->scheduleFactory = $container->get(ScheduleFactory::class);
    }

    #[DataProvider('provideData')]
    public function test(int $maxNumberOfProcesses, int $fileCount, int $expectedNumberOfProcesses): void
    {
        $files = array_fill(0, $fileCount, __DIR__ . '/file.php');

        $schedule = $this->scheduleFactory->create(4, 20, $maxNumberOfProcesses, $files);

        $this->assertSame($expectedNumberOfProcesses, $schedule->getNumberOfProcesses());
    }

    /**
     * @return Iterator<array{int, int, int}>
     */
    public static function provideData(): Iterator
    {
        // default with 4 CPU cores
        yield [0, 500, 4];
        yield [0, 80, 4];
        yield [0, 60, 3];
        yield [0, 40, 2];
        yield [0, 20, 1];
        yield [0, 5, 1];

        // force 1 CPU core
        yield [1, 500, 1];
        yield [1, 80, 1];
        yield [1, 60, 1];
        yield [1, 40, 1];
        yield [1, 20, 1];
        yield [1, 5, 1];

        // force over real available CPU cores
        yield [8, 500, 4];
        yield [8, 80, 4];
        yield [8, 60, 3];
        yield [8, 40, 2];
        yield [8, 20, 1];
        yield [8, 5, 1];

        // preserve 1 CPU core
        yield [-1, 500, 3];
        yield [-1, 80, 3];
        yield [-1, 60, 3];
        yield [-1, 40, 2];
        yield [-1, 20, 1];
        yield [-1, 5, 1];

        // preserve over real available CPU cores
        yield [-7, 500, 1];
        yield [-7, 80, 1];
        yield [-7, 60, 1];
        yield [-7, 40, 1];
        yield [-7, 20, 1];
        yield [-7, 5, 1];
    }
}
