<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

use PhpParser\Comment;
use PhpParser\Node\Expr;
use Rector\Php80\Enum\MatchKind;

final readonly class CondAndExpr
{
    /**
     * @param Expr[]|null $condExprs
     * @param MatchKind::* $matchKind
     * @param Comment[] $comments
     */
    public function __construct(
        private array|null $condExprs,
        private Expr $expr,
        private string $matchKind,
        private array $comments = []
    ) {
    }

    public function getExpr(): Expr
    {
        return $this->expr;
    }

    /**
     * @return Expr[]|null
     */
    public function getCondExprs(): array|null
    {
        // internally checked by PHPStan, cannot be empty array
        if ($this->condExprs === []) {
            return null;
        }

        if ($this->condExprs === null) {
            return null;
        }

        return array_values($this->condExprs);
    }

    /**
     * @return MatchKind::*
     */
    public function getMatchKind(): string
    {
        return $this->matchKind;
    }

    /**
     * @param MatchKind::* $matchKind
     */
    public function equalsMatchKind(string $matchKind): bool
    {
        return $this->matchKind === $matchKind;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }
}
