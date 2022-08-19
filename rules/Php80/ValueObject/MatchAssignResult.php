<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

use PhpParser\Node\Expr\Assign;

final class MatchAssignResult
{
    public function __construct(
        private readonly Assign $assign,
        private readonly bool $shouldRemovePrevoiusStmt
    ) {
    }

    public function getAssign(): Assign
    {
        return $this->assign;
    }

    public function isShouldRemovePreviousStmt(): bool
    {
        return $this->shouldRemovePrevoiusStmt;
    }
}
