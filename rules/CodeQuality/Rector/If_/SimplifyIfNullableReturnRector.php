<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\CodeQuality\TypeResolver\AssignVariableTypeResolver;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\NodeManipulator\IfManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\If_\SimplifyIfNullableReturnRector\SimplifyIfNullableReturnRectorTest
 */
final class SimplifyIfNullableReturnRector extends AbstractRector
{
    public function __construct(
        private readonly IfManipulator $ifManipulator,
        private readonly AssignVariableTypeResolver $assignVariableTypeResolver,
        private readonly VarTagRemover $varTagRemover
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Direct return on if nullable check before return', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        /** @var \stdClass|null $value */
        $value = $this->foo->bar();
        if (! $value instanceof \stdClass) {
            return null;
        }

        return $value;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        return $this->foo->bar();
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
        return [StmtsAwareInterface::class];
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof Return_) {
                continue;
            }

            $previousStmt = $node->stmts[$key - 1] ?? null;
            if (! $previousStmt instanceof If_) {
                continue;
            }

            $if = $previousStmt;
            if ($this->shouldSkip($if)) {
                continue;
            }

            /** @var BooleanNot|Instanceof_ $cond */
            $cond = $if->cond;

            /** @var Instanceof_ $instanceof */
            $instanceof = $cond instanceof BooleanNot ? $cond->expr : $cond;

            // @todo allow property as well
            $variable = $instanceof->expr;

            $class = $instanceof->class;

            if (! $class instanceof Name) {
                continue;
            }

            /** @var Return_ $returnIfStmt */
            $returnIfStmt = $if->stmts[0];

            if ($this->isIfStmtReturnIncorrect($cond, $variable, $returnIfStmt)) {
                continue;
            }

            $previousPreviousStmt = $node->stmts[$key - 2] ?? null;
            if (! $previousPreviousStmt instanceof Expression) {
                continue;
            }

            if (! $previousPreviousStmt->expr instanceof Assign) {
                continue;
            }

            $previousPreviousAssign = $previousPreviousStmt->expr;
            if (! $this->nodeComparator->areNodesEqual($previousPreviousAssign->var, $variable)) {
                continue;
            }

            if ($this->isNextReturnIncorrect($cond, $variable, $stmt)) {
                continue;
            }

            $variableType = $this->assignVariableTypeResolver->resolve($previousPreviousAssign);
            if (! $variableType instanceof UnionType) {
                continue;
            }

            $className = $class->toString();
            $types = $variableType->getTypes();

            $directReturn = $this->processSimplifyNullableReturn(
                $variableType,
                $types,
                $className,
                $previousPreviousStmt,
                $previousPreviousAssign->expr
            );

            if (! $directReturn instanceof Return_) {
                continue;
            }

            // unset previous assign
            unset($node->stmts[$key - 2]);

            // unset previous if
            unset($node->stmts[$key - 1]);

            $node->stmts[$key] = $directReturn;

            return $node;
        }

        return null;
    }

    private function isIfStmtReturnIncorrect(BooleanNot|Instanceof_ $expr, Expr $variable, Return_ $return): bool
    {
        if (! $return->expr instanceof Expr) {
            return true;
        }

        if ($expr instanceof BooleanNot && ! $this->valueResolver->isNull($return->expr)) {
            return true;
        }

        return $expr instanceof Instanceof_ && ! $this->nodeComparator->areNodesEqual($variable, $return->expr);
    }

    private function isNextReturnIncorrect(BooleanNot|Instanceof_ $expr, Expr $variable, Return_ $return): bool
    {
        if (! $return->expr instanceof Expr) {
            return true;
        }

        if ($expr instanceof BooleanNot && ! $this->nodeComparator->areNodesEqual($return->expr, $variable)) {
            return true;
        }

        return $expr instanceof Instanceof_ && ! $this->valueResolver->isNull($return->expr);
    }

    /**
     * @param Type[] $types
     */
    private function processSimplifyNullableReturn(
        UnionType $unionType,
        array $types,
        string $className,
        Expression $expression,
        Expr $expr
    ): ?Return_ {
        if (count($types) > 2) {
            return null;
        }

        if ($types[0] instanceof FullyQualifiedObjectType && $types[1] instanceof NullType && $className === $types[0]->getClassName()) {
            return $this->createDirectReturn($expression, $expr, $unionType);
        }

        if ($types[0] instanceof NullType && $types[1] instanceof FullyQualifiedObjectType && $className === $types[1]->getClassName()) {
            return $this->createDirectReturn($expression, $expr, $unionType);
        }

        if ($this->isNotTypedNullable($types, $className)) {
            return null;
        }

        return $this->createDirectReturn($expression, $expr, $unionType);
    }

    /**
     * @param Type[] $types
     */
    private function isNotTypedNullable(array $types, string $className): bool
    {
        if (! $types[0] instanceof ObjectType) {
            return true;
        }

        if (! $types[1] instanceof NullType) {
            return true;
        }

        return $className !== $types[0]->getClassName();
    }

    private function createDirectReturn(Expression $expression, Expr $expr, UnionType $unionType): Return_
    {
        $exprReturn = new Return_($expr);

        $this->varTagRemover->removeVarPhpTagValueNodeIfNotComment($expression, $unionType);
        $this->mirrorComments($exprReturn, $expression);

        return $exprReturn;
    }

    private function shouldSkip(If_ $if): bool
    {
        if (! $this->ifManipulator->isIfWithOnly($if, Return_::class)) {
            return true;
        }

        $nextNode = $if->getAttribute(AttributeKey::NEXT_NODE);
        if (! $nextNode instanceof Return_) {
            return true;
        }

        $cond = $if->cond;

        if (! $cond instanceof BooleanNot) {
            return ! $cond instanceof Instanceof_;
        }

        return ! $cond->expr instanceof Instanceof_;
    }
}
