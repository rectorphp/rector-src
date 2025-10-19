<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use Nette\Utils\Strings;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\MixedType;
use PHPStan\Type\UnionType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\Value\ValueResolver;

final readonly class VariableInSprintfMaskMatcher
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver,
        private NodeTypeResolver $nodeTypeResolver,
        private ValueResolver $valueResolver,
    ) {

    }

    public function matchMask(
        ClassMethod|Function_|Closure|ArrowFunction $functionLike,
        string $variableName,
        string $mask
    ): bool {
        $stmts = $functionLike instanceof ArrowFunction ? [$functionLike->expr] : $functionLike->getStmts();

        $funcCalls = $this->betterNodeFinder->findInstancesOfScoped($stmts, FuncCall::class);
        $funcCalls = array_values(
            array_filter($funcCalls, fn (FuncCall $funcCall): bool => $this->nodeNameResolver->isName(
                $funcCall->name,
                'sprintf'
            ))
        );

        if (count($funcCalls) !== 1) {
            return false;
        }

        $funcCall = $funcCalls[0];

        if ($funcCall->isFirstClassCallable()) {
            return false;
        }

        $args = $funcCall->getArgs();
        if (count($args) < 2) {
            return false;
        }

        /** @var Arg $messageArg */
        $messageArg = array_shift($args);

        $messageValue = $this->valueResolver->getValue($messageArg->value);
        if (! is_string($messageValue)) {
            return false;
        }

        // match all %s, %d types by position
        $masks = Strings::match($messageValue, '#%[sd]#');

        foreach ($args as $position => $arg) {
            if (! $arg->value instanceof Variable) {
                continue;
            }

            if (! $this-> nodeNameResolver->isName($arg->value, $variableName)) {
                continue;
            }

            if (! isset($masks[$position])) {
                continue;
            }

            $knownMaskOnPosition = $masks[$position];
            if ($knownMaskOnPosition !== $mask) {
                continue;
            }

            $type = $this->nodeTypeResolver->getNativeType($arg->value);
            if ($type instanceof MixedType && $type->getSubtractedType() instanceof UnionType) {
                continue;
            }

            return true;
        }

        return false;
    }
}
