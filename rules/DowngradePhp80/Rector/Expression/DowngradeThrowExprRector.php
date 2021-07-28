<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\Expression;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Expr\Throw_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/throw_expression
 *
 * @see \Rector\Tests\DowngradePhp80\Rector\Expression\DowngradeThrowExprRector\DowngradeThrowExprRectorTest
 */
final class DowngradeThrowExprRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Downgrade throw as expr', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $id = $somethingNonexistent ?? throw new RuntimeException();
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        if (!isset($somethingNonexistent)) {
            throw new RuntimeException();
        }
        $id = $somethingNonexistent;
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
    public function refactor(Node $node): ?Node
    {
        if ($node->expr instanceof Throw_) {
            return null;
        }

        if ($node->expr instanceof Assign) {
            return $this->processAssign($node, $node->expr);
        }

        return $node;
    }

    private function processAssign(Expression $expression, Assign $assign): ?Expression
    {
        if (! $this->hasThrowInAssignExpr($assign)) {
            return null;
        }

        return $expression;
    }

    private function hasThrowInAssignExpr(Assign $assign): bool
    {
        return (bool) $this->betterNodeFinder->findFirst($assign->expr, function (Node $node): bool {
            return $node instanceof Throw_;
        });
    }
}
