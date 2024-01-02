<?php

declare(strict_types=1);

namespace Rector\CodeQuality\ValueObject;

use PhpParser\Comment;
use PhpParser\Node\Expr;

final readonly class KeyAndExpr
{
    /**
     * @param Comment[] $comments
     */
    public function __construct(
        private ?Expr $keyExpr,
        private Expr $expr,
        private array $comments
    ) {
    }

    public function getKeyExpr(): ?Expr
    {
        return $this->keyExpr;
    }

    public function getExpr(): Expr
    {
        return $this->expr;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }
}
