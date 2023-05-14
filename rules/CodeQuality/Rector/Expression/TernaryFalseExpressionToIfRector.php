<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Expression;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PHPStan\Analyser\Scope;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\DeadCode\SideEffect\SideEffectNodeDetector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector\TernaryFalseExpressionToIfRectorTest
 */
final class TernaryFalseExpressionToIfRector extends AbstractScopeAwareRector
{
    public function __construct(
        private readonly SideEffectNodeDetector $sideEffectNodeDetector
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change ternary with false to if and explicit call', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($value, $someMethod)
    {
        $value ? $someMethod->call($value) : false;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($value, $someMethod)
    {
        if ($value) {
            $someMethod->call($value);
        }
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if (! $node->expr instanceof Ternary) {
            return null;
        }

        $ternary = $node->expr;
        if (! $ternary->if instanceof Expr) {
            return null;
        }

        if ($this->sideEffectNodeDetector->detect($ternary->else, $scope)) {
            return null;
        }

        return new If_($ternary->cond, [
            'stmts' => [new Expression($ternary->if)],
        ]);
    }
}
