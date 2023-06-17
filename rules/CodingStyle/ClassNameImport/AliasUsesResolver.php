<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\UseUse;
use Rector\Core\PhpParser\Node\BetterNodeFinder;

final class AliasUsesResolver
{
    public function __construct(
        private readonly UseImportsTraverser $useImportsTraverser,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @param Stmt[] $stmts
     * @return string[]
     */
    public function resolveFromNode(Node $node, array $stmts): array
    {
        if (! $node instanceof Namespace_) {
            /** @var Namespace_[] $namespaces */
            $namespaces = array_filter($stmts, static fn (Stmt $stmt): bool => $stmt instanceof Namespace_);
            foreach ($namespaces as $namespace) {
                $isFoundInNamespace = (bool) $this->betterNodeFinder->findFirst(
                    $namespace->stmts,
                    static fn (Node $subNode): bool => $subNode === $node
                );

                if ($isFoundInNamespace) {
                    $node = $namespace;
                    break;
                }
            }
        }

        if ($node instanceof Namespace_) {
            return $this->resolveFromStmts($node->stmts);
        }

        return [];
    }

    /**
     * @param Stmt[] $stmts
     * @return string[]
     */
    public function resolveFromStmts(array $stmts): array
    {
        $aliasedUses = [];

        $this->useImportsTraverser->traverserStmts($stmts, static function (
            UseUse $useUse,
            string $name
        ) use (&$aliasedUses): void {
            if (! $useUse->alias instanceof Identifier) {
                return;
            }

            $aliasedUses[] = $name;
        });

        return $aliasedUses;
    }
}
