<?php

declare(strict_types=1);

namespace Rector\PhpParser\ValueObject;

use PhpParser\Node\Stmt;
use PhpParser\Token;

final readonly class StmtsAndTokens
{
    /**
     * @param Stmt[] $stmts
     * @param Token[] $tokens
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
     * @return Token[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }
}
