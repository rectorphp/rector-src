<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\ValueObject\Application\File;

final readonly class UseImportsResolver
{
    public function __construct(
        private CurrentFileProvider $currentFileProvider,
        private BetterNodeFinder $betterNodeFinder,
    ) {
    }

    /**
     * @return Use_[]|GroupUse[]
     */
    public function resolve(): array
    {
        // @todo pass argument here :)

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

    private function resolveNamespace(?File $file = null): Namespace_|FileWithoutNamespace|null
    {
        /** @var File|null $file */
        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            return null;
        }

        $newStmts = $file->getNewStmts();

        $namespaces = array_filter($newStmts, static fn (Stmt $stmt): bool => $stmt instanceof Namespace_);

        // multiple namespaces is not supported
        if (count($namespaces) > 1) {
            return null;
        }

        return $this->betterNodeFinder->findFirstInstancesOf($newStmts, [
            Namespace_::class,
            FileWithoutNamespace::class,
        ]);
    }
}
