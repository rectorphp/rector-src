<?php

declare(strict_types=1);

namespace Rector\UseImports;

use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\UseImports\Storage\FileStorage;
use Rector\UseImports\ValueObject\UseImportsScope;

final readonly class UseImportsScopeResolver
{
    public function __construct(
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private FileStorage $fileStorage
    ) {
    }

    public function resolve(string $filePath): UseImportsScope
    {
        $stmts = $this->fileStorage->getStmtsByFile($filePath);

        $namespace = null;
        $namespaceCount = 0;
        $uses = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($stmts, function (Node $node) use (
            &$namespace,
            &$namespaceCount,
            &$uses
        ) {
            if ($node instanceof Namespace_) {
                $namespace = $node;
                ++$namespaceCount;

                return null;
            }

            if ($node instanceof Use_ && $node->type === Use_::TYPE_NORMAL) {
                $uses[] = $node;
            }

            return null;
        });

        return new UseImportsScope($namespace, $namespaceCount, $uses);
    }
}
