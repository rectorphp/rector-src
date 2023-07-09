<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Application;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Use_;

final class UseImportsRemover
{
    /**
     * @param Stmt[] $stmts
     * @param string[] $removedUses
     * @return Stmt[]
     */
    public function removeImportsFromStmts(array $stmts, array $removedUses): array
    {
        $hasChanged = false;

        foreach ($stmts as $key => $stmt) {
            if (! $stmt instanceof Use_) {
                continue;
            }

            $stmt = $this->removeUseFromUse($removedUses, $stmt);

            // remove empty uses
            if ($stmt->uses === []) {
                unset($stmts[$key]);
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            // reset keys
            return array_values($stmts);
        }

        return $stmts;
    }

    /**
     * @param string[] $removedUses
     */
    private function removeUseFromUse(array $removedUses, Use_ $use): Use_
    {
        if ($removedUses === []) {
            return $use;
        }

        foreach ($use->uses as $usesKey => $useUse) {
            foreach ($removedUses as $removedUse) {
                if ($useUse->name->toString() !== $removedUse) {
                    continue;
                }

                unset($use->uses[$usesKey]);
            }
        }

        return $use;
    }
}
