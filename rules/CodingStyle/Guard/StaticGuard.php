<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Guard;

use Nette\Neon\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use Rector\Core\PhpParser\Node\BetterNodeFinder;

final class StaticGuard
{
    public function __construct(private readonly BetterNodeFinder $betterNodeFinder)
    {
    }

    public function isLegal(Closure|ArrowFunction $node): bool
    {
        if ($node->static) {
            return false;
        }

        $nodes = $node instanceof Closure
            ? $node->stmts
            : [$node->expr];

        $hasThisVariale = (bool) $this->betterNodeFinder->findFirst(
            $nodes,
            static fn (Node $subNode): bool => $subNode instanceof Variable && $subNode->name === 'this'
        );

        if ($hasThisVariale) {
            return false;
        }

        // verify has static call call non static method

        return true;
    }
}
