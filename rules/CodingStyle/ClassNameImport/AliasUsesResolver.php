<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport;

use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\UseItem;
use Rector\PhpParser\Node\FileNode;
use Rector\ValueObject\Application\File;

final class AliasUsesResolver
{
    private ?File $cachedFile = null;

    private int $cachedVersion = -1;

    /**
     * @var string[]
     */
    private array $cachedAliasedUses = [];

    public function __construct(
        private readonly UseImportsTraverser $useImportsTraverser
    ) {
    }

    /**
     * Aliased uses are file-global, so resolve once per file stmts version and reuse
     * across every imported name in the same traversal
     *
     * @return string[]
     */
    public function resolveFromFile(File $file): array
    {
        $newStmtsVersion = $file->getNewStmtsVersion();
        if ($file === $this->cachedFile && $newStmtsVersion === $this->cachedVersion) {
            return $this->cachedAliasedUses;
        }

        $this->cachedFile = $file;
        $this->cachedVersion = $newStmtsVersion;
        $this->cachedAliasedUses = $this->resolveFromNewStmts($file->getNewStmts());

        return $this->cachedAliasedUses;
    }

    /**
     * @param Stmt[] $stmts
     * @return string[]
     */
    public function resolveFromStmts(array $stmts): array
    {
        $aliasedUses = [];

        /** @param Use_::TYPE_* $useType */
        $this->useImportsTraverser->traverserStmts($stmts, static function (
            int $useType,
            UseItem $useItem,
            string $name
        ) use (&$aliasedUses): void {
            if ($useType !== Use_::TYPE_NORMAL) {
                return;
            }

            if (! $useItem->alias instanceof Identifier) {
                return;
            }

            $aliasedUses[] = $name;
        });

        return $aliasedUses;
    }

    /**
     * @param Stmt[] $stmts
     * @return string[]
     */
    private function resolveFromNewStmts(array $stmts): array
    {
        /** @var Namespace_[]|FileNode[] $namespaces */
        $namespaces = array_filter(
            $stmts,
            static fn (Stmt $stmt): bool => $stmt instanceof Namespace_ || $stmt instanceof FileNode
        );

        if (count($namespaces) !== 1) {
            return [];
        }

        $namespace = current($namespaces);

        return $this->resolveFromStmts($namespace->stmts);
    }
}
