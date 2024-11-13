<?php

declare(strict_types=1);

namespace Rector\Tests\Util;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Util\BatchSplitter;

final class BatchSplitterTest extends AbstractLazyTestCase
{
    private BatchSplitter $batchSplitter;

    protected function setUp(): void
    {
        $this->batchSplitter = $this->make(BatchSplitter::class);
    }

    /**
     * @param int[] $items
     * @param int[] $expectedItems
     */
    #[DataProvider('provideData')]
    public function testGetItemsInBatch(
        array $items,
        int $batchIndex,
        int $batchTotal,
        array $expectedItems
    ): void {
        $batchItems = $this->batchSplitter->getItemsInBatch($items, $batchIndex, $batchTotal);
        $this->assertSame($expectedItems, $batchItems);
    }

    public static function provideData(): Iterator
    {
        yield [[], 1, 4, []];
        yield [[1, 2, 3, 4], 0, 0, [1, 2, 3, 4]];
        yield [[1, 2, 3, 4], 3, 2, [1, 2, 3, 4]];
        yield [[1, 2, 3, 4], 0, 2, [1, 2]];
        yield [[1, 2, 3, 4], 1, 2, [3, 4]];
        yield [[1, 2, 3], 0, 2, [1, 2]];
        yield [[1, 2, 3], 1, 2, [3]];
        yield [[1, 2, 3, 4], 0, 3, [1, 2]];
        yield [[1, 2, 3, 4], 1, 3, [3, 4]];
        yield [[1, 2, 3, 4], 2, 3, []];
        yield [[1, 2, 3], 0, 4, [1]];
        yield [[1, 2, 3], 3, 4, []];
    }
}
