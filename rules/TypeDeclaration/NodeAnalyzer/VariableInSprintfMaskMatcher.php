<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use Nette\Utils\Strings;
use PhpParser\Node\Arg;
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

    public function matchMask(ClassMethod|Function_ $functionLike, string $variableName, string $mask): bool
    {
        $funcCalls = $this->betterNodeFinder->findInstancesOfScoped((array) $functionLike->stmts, FuncCall::class);

        foreach ($funcCalls as $funcCall) {
            if (! $this->nodeNameResolver->isName($funcCall->name, 'sprintf')) {
                continue;
            }

            if ($funcCall->isFirstClassCallable()) {
                continue;
            }

            $args = $funcCall->getArgs();
            if (count($args) < 2) {
                continue;
            }

            /** @var Arg $messageArg */
            $messageArg = array_shift($args);

            $messageValue = $this->valueResolver->getValue($messageArg->value);
            if (! is_string($messageValue)) {
                continue;
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

                $type = $this->nodeTypeResolver->getType($arg->value);
                if ($type instanceof MixedType && $type->getSubtractedType() instanceof UnionType) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }
}
