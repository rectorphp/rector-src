<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\UseUse;

final class AliasUsesResolver
{
    public function __construct(
        private readonly UseImportsTraverser $useImportsTraverser
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
            if (count($namespaces) !== 1) {
                return [];
            }

            $node = current($namespaces);
        }

        return $this->resolveFromStmts($node->stmts);
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
            if (!$useUse->type === Stmt\Use_::TYPE_NORMAL) {
                return;
            }
            if (! $useUse->alias instanceof Identifier) {
                return;
            }

            $aliasedUses[] = $name;
        });

        return $aliasedUses;
    }
}
