<?php

declare(strict_types=1);

namespace Rector\Util;

final class BatchSplitter
{
    /**
     * @template T
     * @param T[] $items
     * @return T[]
     */
    public function getItemsInBatch(array $items, int $batchIndex, int $batchTotal): array
    {
        if ($batchTotal !== 0 && $batchIndex < $batchTotal) {
            $numItems = count($items);
            $chunkSize = (int) ceil($numItems / $batchTotal);
            if ($chunkSize > 0) {
                $chunks = array_chunk($items, $chunkSize);
                $items = $chunks[$batchIndex] ?? [];
            }
        }

        return $items;
    }
}
