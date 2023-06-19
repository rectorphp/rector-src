<?php

declare(strict_types=1);

namespace Rector\Naming\Matcher;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Foreach_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\ValueObject\VariableAndCallForeach;
use Rector\NodeNameResolver\NodeNameResolver;

final class ForeachMatcher
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly CallMatcher $callMatcher,
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function match(Foreach_ $foreach, FunctionLike $functionLike): ?VariableAndCallForeach
    {
        $call = $this->callMatcher->matchCall($foreach);
        if ($call === null) {
            return null;
        }

        if (! $foreach->valueVar instanceof Variable) {
            return null;
        }

        $variableName = $this->nodeNameResolver->getName($foreach->valueVar);
        if ($variableName === null) {
            return null;
        }

        return new VariableAndCallForeach($foreach->valueVar, $call, $variableName, $functionLike);
    }
}
