<?php

declare(strict_types=1);

namespace Rector\PhpParser\ValueObject;

use PhpParser\Node\Stmt;

final readonly class StmtsAndTokens
{
    /**
     * @param Stmt[] $stmts
     * @param array<int, array{int, string, int}|string> $tokens
     */
    public function __construct(
        private array $stmts,
        private array $tokens
    ) {
    }

    /**
     * @return Stmt[]
     */
    public function getStmts(): array
    {
        return $this->stmts;
    }

    /**
     * @return array<int, array{int, string, int}|string>
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }
}
