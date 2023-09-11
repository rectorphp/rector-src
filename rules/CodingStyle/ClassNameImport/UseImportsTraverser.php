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
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class UseImportsTraverser
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @param Stmt[] $stmts
     * @param callable(UseUse $useUse, string $name): void $callable
     */
    public function traverserStmts(array $stmts, callable $callable): void
    {
        $this->traverseForType($stmts, $callable, Use_::TYPE_NORMAL);
    }

    /**
     * @param Stmt[] $stmts
     * @param callable(UseUse $useUse, string $name): void $callable
     */
    public function traverserStmtsForConstants(array $stmts, callable $callable): void
    {
        $this->traverseForType($stmts, $callable, Use_::TYPE_CONSTANT);
    }

    /**
     * @param Stmt[] $stmts
     * @param callable(UseUse $useUse, string $name): void $callable
     */
    public function traverserStmtsForFunctions(array $stmts, callable $callable): void
    {
        $this->traverseForType($stmts, $callable, Use_::TYPE_FUNCTION);
    }

    /**
     * @param callable(UseUse $useUse, string $name): void $callable
     * @param Stmt[] $stmts
     */
    private function traverseForType(array $stmts, callable $callable, int $desiredType): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($stmts, function (Node $node) use (
            $callable,
            $desiredType
        ): ?int {
            if ($node instanceof Namespace_ || $node instanceof FileWithoutNamespace) {
                // traverse into namespaces
                return null;
            }

            if ($node instanceof Use_ && $node->type === $desiredType) {
                foreach ($node->uses as $useUse) {
                    $name = $this->nodeNameResolver->getName($useUse);
                    if ($name === null) {
                        continue;
                    }

                    $callable($useUse, $name);
                }
            } elseif ($node instanceof GroupUse) {
                $this->processGroupUse($node, $desiredType, $callable);
            }

            return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
        });
    }

    /**
     * @param callable(UseUse $useUse, string $name): void $callable
     */
    private function processGroupUse(GroupUse $groupUse, int $desiredType, callable $callable): void
    {
        if ($groupUse->type !== Use_::TYPE_UNKNOWN) {
            return;
        }

        $prefixName = $groupUse->prefix->toString();

        foreach ($groupUse->uses as $useUse) {
            if ($useUse->type !== $desiredType) {
                continue;
            }

            $name = $prefixName . '\\' . $this->nodeNameResolver->getName($useUse);
            $callable($useUse, $name);
        }
    }
}
