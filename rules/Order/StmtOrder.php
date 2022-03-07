<?php

declare(strict_types=1);

namespace Rector\Order;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;

/**
 * @see \Rector\Tests\Order\StmtOrderTest
 */
final class StmtOrder
{
    /**
     * @param array<int, string> $desiredStmtOrder
     * @param array<int, string> $currentStmtOrder
     * @return array<int, int>
     */
    public function createOldToNewKeys(array $desiredStmtOrder, array $currentStmtOrder): array
    {
        $newKeys = [];
        foreach ($desiredStmtOrder as $singleDesiredStmtOrder) {
            foreach ($currentStmtOrder as $currentKey => $classMethodName) {
                if ($classMethodName === $singleDesiredStmtOrder) {
                    $newKeys[] = $currentKey;
                }
            }
        }

        $oldKeys = array_values($newKeys);
        sort($oldKeys);

        /** @var array<int, int> $oldToNewKeys */
        $oldToNewKeys = array_combine($oldKeys, $newKeys);

        return $oldToNewKeys;
    }

    /**
     * @param array<int, int> $oldToNewKeys
     */
    public function reorderClassStmtsByOldToNewKeys(ClassLike $classLike, array $oldToNewKeys): void
    {
        /** @var array<int, Node\Stmt> $reorderedStmts */
        $reorderedStmts = [];

        $stmtCount = count($classLike->stmts);

        foreach ($classLike->stmts as $key => $stmt) {
            if (! array_key_exists($key, $oldToNewKeys)) {
                $reorderedStmts[$key] = $stmt;
                continue;
            }

            // reorder here
            $newKey = $oldToNewKeys[$key];

            $reorderedStmts[$key] = $classLike->stmts[$newKey];
        }

        for ($i = 0; $i < $stmtCount; ++$i) {
            if (! array_key_exists($i, $reorderedStmts)) {
                continue;
            }

            $classLike->stmts[$i] = $reorderedStmts[$i];
        }
    }
}
