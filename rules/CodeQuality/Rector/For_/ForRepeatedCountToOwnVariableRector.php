<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\For_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\For_;
use PHPStan\Analyser\Scope;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Naming\Naming\VariableNaming;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector\ForRepeatedCountToOwnVariableRectorTest
 */
final class ForRepeatedCountToOwnVariableRector extends AbstractScopeAwareRector
{
    public function __construct(
        private readonly VariableNaming $variableNaming
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change count() in for function to own variable',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($items)
    {
        for ($i = 5; $i <= count($items); $i++) {
            echo $items[$i];
        }
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($items)
    {
        $itemsCount = count($items);
        for ($i = 5; $i <= $itemsCount; $i++) {
            echo $items[$i];
        }
    }
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
        return [For_::class];
    }

    /**
     * @param For_ $node
     * @return Stmt[]|null
     */
    public function refactorWithScope(Node $node, Scope $scope): ?array
    {
        $variableName = null;
        $countInCond = null;

        foreach ($node->cond as $condExpr) {
            if (! $condExpr instanceof Smaller && ! $condExpr instanceof SmallerOrEqual) {
                continue;
            }

            if (! $condExpr->right instanceof FuncCall) {
                continue;
            }

            $funcCall = $condExpr->right;
            if (! $this->isName($funcCall, 'count')) {
                continue;
            }

            $variableName = $this->variableNaming->resolveFromFuncCallFirstArgumentWithSuffix(
                $funcCall,
                'Count',
                'itemsCount',
                $scope
            );

            $countInCond = $condExpr->right;

            $condExpr->right = new Variable($variableName);
        }

        if (! is_string($variableName) || ! $countInCond instanceof Expr) {
            return null;
        }

        $countAssign = new Assign(new Variable($variableName), $countInCond);
        return [new Expression($countAssign), $node];
    }
}
