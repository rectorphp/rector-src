<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\BooleanOr;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\BooleanOr\RemoveRedundantNullableTypeCheckRector\RemoveRedundantNullableTypeCheckRectorTest
 */
final class RemoveRedundantNullableTypeCheckRector extends AbstractRector
{
    public function __construct(
        private readonly ValueResolver $valueResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove is_<type>() check that can never fail next to null compare on a nullable value',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(?string $value)
    {
        if ($value === null || ! is_string($value)) {
            return;
        }
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(?string $value)
    {
        if ($value === null) {
            return;
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
        return [BooleanOr::class];
    }

    /**
     * @param BooleanOr $node
     */
    public function refactor(Node $node): ?Expr
    {
        if (! $node->left instanceof Identical) {
            return null;
        }

        $nullComparedExpr = $this->matchNullComparedExpr($node->left);
        if (! $nullComparedExpr instanceof Variable) {
            return null;
        }

        if (! $node->right instanceof BooleanNot) {
            return null;
        }

        $funcCall = $node->right->expr;
        if (! $funcCall instanceof FuncCall) {
            return null;
        }

        if ($funcCall->isFirstClassCallable()) {
            return null;
        }

        if (count($funcCall->getArgs()) !== 1) {
            return null;
        }

        if (! $this->nodeComparator->areNodesEqual($funcCall->getArgs()[0]->value, $nullComparedExpr)) {
            return null;
        }

        $funcCallName = $this->getName($funcCall);
        if ($funcCallName === null) {
            return null;
        }

        $comparedType = $this->getType($nullComparedExpr);
        if (! TypeCombinator::containsNull($comparedType)) {
            return null;
        }

        if (! $this->isAlwaysMatchingType($funcCallName, TypeCombinator::removeNull($comparedType))) {
            return null;
        }

        return $node->left;
    }

    private function matchNullComparedExpr(Identical $identical): ?Expr
    {
        if ($this->valueResolver->isNull($identical->left)) {
            return $identical->right;
        }

        if ($this->valueResolver->isNull($identical->right)) {
            return $identical->left;
        }

        return null;
    }

    private function isAlwaysMatchingType(string $funcCallName, Type $type): bool
    {
        return match ($funcCallName) {
            'is_string' => $type->isString()
                ->yes(),
            'is_int', 'is_integer', 'is_long' => $type->isInteger()
                ->yes(),
            'is_float', 'is_double' => $type->isFloat()
                ->yes(),
            'is_bool' => $type->isBoolean()
                ->yes(),
            'is_array' => $type->isArray()
                ->yes(),
            'is_object' => $type->isObject()
                ->yes(),
            default => false,
        };
    }
}
