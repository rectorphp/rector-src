<?php

declare(strict_types=1);

namespace Rector\Naming\Matcher;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use Rector\Naming\ValueObject\VariableAndCallForeach;
use Rector\NodeNameResolver\NodeNameResolver;

final readonly class ForeachMatcher
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private CallMatcher $callMatcher,
    ) {
    }

    public function match(Foreach_ $foreach, ClassMethod|Closure|Function_ $functionLike): ?VariableAndCallForeach
    {
        $call = $this->callMatcher->matchCall($foreach);
        if (! $call instanceof Node) {
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
