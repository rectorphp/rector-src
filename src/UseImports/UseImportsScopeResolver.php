<?php

declare(strict_types=1);

namespace Rector\UseImports;

use PhpParser\Node;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PHPStan\Parser\Parser;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\UseImports\ValueObject\UseImportsScope;

final class UseImportsScopeResolver
{
    public function __construct(
        private readonly Parser $parser,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
    ) {
    }

    public function resolve(string $filePath): UseImportsScope
    {
        // @todo cache
        $stmts = $this->parser->parseFile($filePath);

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

            if ($node instanceof GroupUse) {
                $uses[] = $node;
            }

            if ($node instanceof Use_) {
                if ($node->type === Use_::TYPE_NORMAL) {
                    $uses[] = $node;
                }
            }

            return null;
        });

        return new UseImportsScope($namespace, $namespaceCount, $uses);
    }
}
