<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\NodeTraverser;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;

final class UseImportsTraverser
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @param Stmt[] $stmts
     * @param callable(Use_::TYPE_* $useType, UseUse $useUse, string $name): void $callable
     */
    public function traverserStmts(array $stmts, callable $callable): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($stmts, function (Node $node) use (
            $callable,
        ): ?int {
            if ($node instanceof Namespace_ || $node instanceof FileWithoutNamespace) {
                // traverse into namespaces
                return null;
            }

            if ($node instanceof Use_) {
                foreach ($node->uses as $useUse) {
                    $name = $this->nodeNameResolver->getName($useUse);
                    if ($name === null) {
                        continue;
                    }

                    $callable($node->type, $useUse, $name);
                }
            } elseif ($node instanceof GroupUse) {
                $this->processGroupUse($node, $callable);
            }

            return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
        });
    }

    /**
     * @param callable(Use_::TYPE_* $useType, UseUse $useUse, string $name): void $callable
     */
    private function processGroupUse(GroupUse $groupUse, callable $callable): void
    {
        if ($groupUse->type !== Use_::TYPE_UNKNOWN) {
            return;
        }

        $prefixName = $groupUse->prefix->toString();

        foreach ($groupUse->uses as $useUse) {
            $name = $prefixName . '\\' . $this->nodeNameResolver->getName($useUse);
            $callable($useUse->type, $useUse, $name);
        }
    }
}
