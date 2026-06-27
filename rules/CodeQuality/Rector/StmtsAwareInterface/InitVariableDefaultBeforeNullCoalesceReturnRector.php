<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\StmtsAwareInterface;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use Rector\PhpParser\Enum\NodeGroup;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\InitVariableDefaultBeforeNullCoalesceReturnRector\InitVariableDefaultBeforeNullCoalesceReturnRectorTest
 */
final class InitVariableDefaultBeforeNullCoalesceReturnRector extends AbstractRector
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Initialize a variable with its default value before it is filled, instead of null coalescing a possibly undefined variable on return',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
function run(array $keys): array
{
    foreach ($keys as $key) {
        $result[$key] = $key;
    }

    return $result ?? [];
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
function run(array $keys): array
{
    $result = [];
    foreach ($keys as $key) {
        $result[$key] = $key;
    }

    return $result;
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
        return NodeGroup::STMTS_AWARE;
    }

    /**
     * @param StmtsAware $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        $stmts = $node->stmts;

        foreach ($stmts as $returnKey => $stmt) {
            if (! $stmt instanceof Return_) {
                continue;
            }

            if (! $stmt->expr instanceof Coalesce) {
                continue;
            }

            $coalesce = $stmt->expr;
            if (! $coalesce->left instanceof Variable) {
                continue;
            }

            if (! $this->isLiteralDefault($coalesce->right)) {
                continue;
            }

            $variableName = $this->getName($coalesce->left);
            if ($variableName === null) {
                continue;
            }

            if (! $this->isFilledOnlyAsArrayDim($stmts, $variableName)) {
                continue;
            }

            $insertKey = $this->resolveFirstArrayDimWriteKey($stmts, $returnKey, $variableName);
            if ($insertKey === null) {
                continue;
            }

            $initExpression = new Expression(new Assign(new Variable($variableName), $coalesce->right));
            array_splice($stmts, $insertKey, 0, [$initExpression]);

            $stmt->expr = $coalesce->left;

            $node->stmts = $stmts;

            return $node;
        }

        return null;
    }

    private function isLiteralDefault(Expr $expr): bool
    {
        return $expr instanceof Array_ || $expr instanceof Scalar || $expr instanceof ConstFetch;
    }

    /**
     * The variable must be written at least once as an array dimension and never assigned directly,
     * otherwise the null coalesce guards a real value, not just an undefined variable.
     *
     * @param Stmt[] $stmts
     */
    private function isFilledOnlyAsArrayDim(array $stmts, string $variableName): bool
    {
        $hasArrayDimAssign = false;

        $assigns = $this->betterNodeFinder->findInstancesOf($stmts, [Assign::class]);
        foreach ($assigns as $assign) {
            if ($assign->var instanceof Variable && $this->isName($assign->var, $variableName)) {
                return false;
            }

            if (! $assign->var instanceof ArrayDimFetch) {
                continue;
            }

            $rootVariable = $this->resolveArrayDimRootVariable($assign->var);
            if ($rootVariable instanceof Variable && $this->isName($rootVariable, $variableName)) {
                $hasArrayDimAssign = true;
            }
        }

        return $hasArrayDimAssign;
    }

    /**
     * @param Stmt[] $stmts
     */
    private function resolveFirstArrayDimWriteKey(array $stmts, int $returnKey, string $variableName): ?int
    {
        foreach ($stmts as $key => $stmt) {
            if ($key >= $returnKey) {
                return null;
            }

            $foundArrayDimAssign = $this->betterNodeFinder->findFirst($stmt, function (Node $subNode) use (
                $variableName
            ): bool {
                if (! $subNode instanceof Assign) {
                    return false;
                }

                if (! $subNode->var instanceof ArrayDimFetch) {
                    return false;
                }

                $expr = $this->resolveArrayDimRootVariable($subNode->var);
                return $expr instanceof Variable && $this->isName($expr, $variableName);
            });

            if ($foundArrayDimAssign instanceof Node) {
                return $key;
            }
        }

        return null;
    }

    private function resolveArrayDimRootVariable(ArrayDimFetch $arrayDimFetch): Expr
    {
        $currentVariable = $arrayDimFetch->var;
        while ($currentVariable instanceof ArrayDimFetch) {
            $currentVariable = $currentVariable->var;
        }

        return $currentVariable;
    }
}
