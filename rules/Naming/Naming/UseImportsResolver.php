<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\PhpParser\Node\FileNode;
use Rector\ValueObject\Application\File;

final readonly class UseImportsResolver
{
    public function __construct(
        private CurrentFileProvider $currentFileProvider
    ) {
    }

    /**
     * @return array<Use_|GroupUse>
     */
    public function resolve(): array
    {
        $namespace = $this->resolveNamespace();
        if (! $namespace instanceof Node) {
            return [];
        }

        return array_filter(
            $namespace->stmts,
            static fn (Stmt $stmt): bool => $stmt instanceof Use_ || $stmt instanceof GroupUse
        );
    }

    /**
     * @api
     * @return Use_[]
     */
    public function resolveBareUses(): array
    {
        $namespace = $this->resolveNamespace();
        if (! $namespace instanceof Node) {
            return [];
        }

        return array_filter($namespace->stmts, static fn (Stmt $stmt): bool => $stmt instanceof Use_);
    }

    public function resolvePrefix(Use_|GroupUse $use): string
    {
        return $use instanceof GroupUse
            ? $use->prefix . '\\'
            : '';
    }

    private function resolveNamespace(): Namespace_|FileNode|null
    {
        /** @var File|null $file */
        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            return null;
        }

        $newStmts = $file->getNewStmts();
        if ($newStmts === []) {
            return null;
        }

        if ($newStmts[0] instanceof FileNode) {
            $fileNode = $newStmts[0];
            return $fileNode->getNamespace();
        }

        return null;
    }
}
