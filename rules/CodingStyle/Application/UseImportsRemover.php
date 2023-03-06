<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Application;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Use_;
use Rector\Core\Configuration\RectorConfigProvider;

final class UseImportsRemover
{
    public function __construct(private readonly RectorConfigProvider $rectorConfigProvider)
    {
    }

    /**
     * @param Stmt[] $stmts
     * @param string[] $removedUses
     * @return Stmt[]
     */
    public function removeImportsFromStmts(array $stmts, array $removedUses): array
    {
        /**
         * Verify import name to cover conflict on rename+import,
         * but without $rectorConfig->removeUnusedImports() used
         */
        if (! $this->rectorConfigProvider->shouldImportNames()) {
            return $stmts;
        }

        foreach ($stmts as $stmtKey => $stmt) {
            if (! $stmt instanceof Use_) {
                continue;
            }

            $this->removeUseFromUse($removedUses, $stmt);

            // nothing left → remove
            if ($stmt->uses === []) {
                unset($stmts[$stmtKey]);
            }
        }

        return $stmts;
    }

    /**
     * @param string[] $removedUses
     */
    private function removeUseFromUse(array $removedUses, Use_ $use): void
    {
        foreach ($use->uses as $usesKey => $useUse) {
            foreach ($removedUses as $removedShortUse) {
                if ($useUse->name->toString() === $removedShortUse) {
                    unset($use->uses[$usesKey]);
                }
            }
        }
    }
}
