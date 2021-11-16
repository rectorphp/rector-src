<?php

declare(strict_types=1);

namespace Rector\DowngradePhp55\Rector\Isset_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @changelog https://wiki.php.net/rfc/empty_isset_exprs
 *
 * @see Rector\Tests\DowngradePhp55\Rector\Isset_\DowngradeArbitraryExpressionArgsToEmptyAndIssetRector\DowngradeArbitraryExpressionArgsToEmptyAndIssetRectorTest
 */
final class DowngradeArbitraryExpressionArgsToEmptyAndIssetRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Downgrade arbitrary expression arguments to empty() and isset()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
if (isset(some_function())) {
    // ...
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
if (some_function() !== null) {
    // ...
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
        return [Isset_::class, Empty_::class];
    }

    /**
     * @param Isset_|Empty_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        return $node instanceof Empty_
            ? $this->refactorEmpty($node)
            : $this->refactorIsset($node);
    }

    private function shouldSkip(Isset_|Empty_ $node): bool
    {
        if ($node instanceof Empty_) {
            return $node->expr instanceof Variable;
        }

        foreach ($node->vars as $var) {
            if (! $var instanceof Variable) {
                return false;
            }
        }

        return true;
    }

    private function refactorEmpty(Empty_ $empty): BooleanNot
    {
        return new BooleanNot($empty->expr);
    }

    private function refactorIsset(Isset_ $isset): Expr
    {
        $exprs = [];
        $currentExpr = null;
        foreach ($isset->vars as $var) {
            if (! $var instanceof Variable) {
                $currentExpr = new NotIdentical($var, $this->nodeFactory->createNull());
                $exprs[] = $currentExpr;
                continue;
            }

            if (! $currentExpr instanceof Isset_) {
                $currentExpr = new Isset_([]);
                $exprs[] = $currentExpr;
            }

            $currentExpr->vars[] = $var;
        }

        Assert::minCount($exprs, 1);

        return $this->joinWithBooleanAnd($exprs);
    }

    /**
     * @param non-empty-array<int, Expr> $exprs
     */
    private function joinWithBooleanAnd(array $exprs): Expr
    {
        $expr = $exprs[0];
        $nbExprs = count($exprs);
        for ($i = 1; $i < $nbExprs; ++$i) {
            $expr = new BooleanAnd($expr, $exprs[$i]);
        }

        return $expr;
    }
}
