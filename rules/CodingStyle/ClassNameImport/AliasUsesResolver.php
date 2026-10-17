<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport;

use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\UseItem;

final readonly class AliasUsesResolver
{
    public function __construct(
        private UseImportsTraverser $useImportsTraverser
    ) {
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
}
