<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Identical;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PHPStan\Type\BooleanType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector\SimplifyBoolIdenticalTrueRectorTest
 */
final class SimplifyBoolIdenticalTrueRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Symplify bool value compare to true or false',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(bool $value, string $items)
    {
         $match = in_array($value, $items, TRUE) === TRUE;
         $match = in_array($value, $items, TRUE) !== FALSE;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(bool $value, string $items)
    {
         $match = in_array($value, $items, TRUE);
         $match = in_array($value, $items, TRUE);
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
        return [Identical::class, NotIdentical::class];
    }

    /**
     * @param Identical|NotIdentical $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->nodeTypeResolver->isStaticType(
            $node->left,
            BooleanType::class
        ) && ! $this->valueResolver->isTrueOrFalse($node->left)) {
            return $this->processBoolTypeToNotBool($node, $node->left, $node->right);
        }

        if (! $this->nodeTypeResolver->isStaticType($node->right, BooleanType::class)) {
            return null;
        }

        if ($this->valueResolver->isTrueOrFalse($node->right)) {
            return null;
        }

        return $this->processBoolTypeToNotBool($node, $node->right, $node->left);
    }

    private function processBoolTypeToNotBool(Node $node, Expr $leftExpr, Expr $rightExpr): ?Expr
    {
        if ($node instanceof Identical) {
            return $this->refactorIdentical($leftExpr, $rightExpr);
        }

        if ($node instanceof NotIdentical) {
            return $this->refactorNotIdentical($leftExpr, $rightExpr);
        }

        return null;
    }

    private function refactorIdentical(Expr $leftExpr, Expr $rightExpr): ?Expr
    {
        if ($this->valueResolver->isTrue($rightExpr)) {
            return $leftExpr;
        }

        if ($this->valueResolver->isFalse($rightExpr)) {
            // prevent !!
            if ($leftExpr instanceof BooleanNot) {
                return $leftExpr->expr;
            }

            return new BooleanNot($leftExpr);
        }

        return null;
    }

    private function refactorNotIdentical(Expr $leftExpr, Expr $rightExpr): ?Expr
    {
        if ($this->valueResolver->isFalse($rightExpr)) {
            return $leftExpr;
        }

        if ($this->valueResolver->isTrue($rightExpr)) {
            return new BooleanNot($leftExpr);
        }

        return null;
    }
}
