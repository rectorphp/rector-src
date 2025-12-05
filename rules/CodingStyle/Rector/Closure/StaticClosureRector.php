<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\Closure;

use PhpParser\Node;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use Rector\CodingStyle\Guard\StaticGuard;
use Rector\NodeAnalyzer\CallLikeExpectsThisBindedClosureArgsAnalyzer;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\StaticClosureRectorTest
 */
final class StaticClosureRector extends AbstractRector
{
    private const CLOSURE_USES_THIS = 'has_this_closure';

    public function __construct(
        private readonly StaticGuard $staticGuard,
        private readonly CallLikeExpectsThisBindedClosureArgsAnalyzer $callLikeExpectsThisBindedClosureArgsAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes Closure to be static when possible',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
function () {
    if (rand(0, 1)) {
        return 1;
    }

    return 2;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
static function () {
    if (rand(0, 1)) {
        return 1;
    }

    return 2;
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Closure::class];
    }

    #[Override]
    public function beforeTraverse(array $nodes): array
    {
        parent::beforeTraverse($nodes);

        $this->traverseNodesWithCallable($nodes, function (Node $node): ?CallLike {
            if (
                ! $node instanceof Node\Expr\MethodCall
                && ! $node instanceof Node\Expr\StaticCall
                && ! $node instanceof Node\Expr\FuncCall
            ) {
                return null;
            }

            if ($node->isFirstClassCallable()) {
                return null;
            }

            $args = $this->callLikeExpectsThisBindedClosureArgsAnalyzer->getArgsUsingThisBindedClosure($node);

            if ($args === []) {
                return null;
            }

            foreach ($args as $arg) {
                if ($arg->value instanceof Closure && ! $arg->hasAttribute(self::CLOSURE_USES_THIS)) {
                    $arg->value->setAttribute(self::CLOSURE_USES_THIS, true);
                }
            }

            return $node;
        });

        return $nodes;
    }

    /**
     * @param Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->hasAttribute(self::CLOSURE_USES_THIS)) {
            return null;
        }

        if (! $this->staticGuard->isLegal($node)) {
            return null;
        }

        $node->static = true;
        return $node;
    }
}
