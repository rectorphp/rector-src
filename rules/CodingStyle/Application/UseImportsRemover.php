<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Application;

use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Renaming\Collector\RenamedNameCollector;

final readonly class UseImportsRemover
{
    public function __construct(
        private RenamedNameCollector $renamedNameCollector
    ) {
    }

    /**
     * @param string[] $removedUses
     */
    public function removeImportsFromStmts(FileWithoutNamespace|Namespace_ $node, array $removedUses): bool
    {
        $hasRemoved = false;
        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof Use_) {
                continue;
            }

            if ($this->removeUseFromUse($removedUses, $stmt)) {
                $node->stmts[$key] = $stmt;
                $hasRemoved = true;
            }

            // remove empty uses
            if ($stmt->uses === []) {
                unset($node->stmts[$key]);
            }
        }

        if ($hasRemoved) {
            $node->stmts = array_values($node->stmts);
        }

        return $hasRemoved;
    }

    /**
     * @param string[] $removedUses
     */
    private function removeUseFromUse(array $removedUses, Use_ $use): bool
    {
        $hasChanged = false;
        foreach ($use->uses as $usesKey => $useUse) {
            $useName = $useUse->name->toString();
            if (! in_array($useName, $removedUses, true)) {
                continue;
            }

            if (! $this->renamedNameCollector->has($useName)) {
                continue;
            }

            unset($use->uses[$usesKey]);
            $hasChanged = true;
        }

        if ($hasChanged) {
            $use->uses = array_values($use->uses);
        }

        return $hasChanged;
    }
}
