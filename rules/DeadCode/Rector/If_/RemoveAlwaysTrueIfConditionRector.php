<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeVisitor;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\IntersectionType;
use Rector\DeadCode\NodeAnalyzer\SafeLeftTypeBooleanAndOrAnalyzer;
use Rector\NodeAnalyzer\ExprAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\RemoveAlwaysTrueIfConditionRectorTest
 */
final class RemoveAlwaysTrueIfConditionRector extends AbstractRector
{
    /**
     * Functions that depend on runtime state (autoloading, eval(), loaded extensions).
     * PHPStan may narrow their result to a constant true, but it can change at runtime,
     * so the condition must not be treated as always true.
     *
     * @var string[]
     */
    private const array RUNTIME_STATE_FUNCTIONS = [
        'class_exists',
        'interface_exists',
        'trait_exists',
        'enum_exists',
        'function_exists',
        'method_exists',
        'property_exists',
        'defined',
        'extension_loaded',
    ];

    /**
     * Type-assertion guards. Their "always true" result may come from a phpdoc-narrowed type
     * that runtime can violate (e.g. user config, decoded JSON), so when the right operand reuses
     * the guarded variable, removing the guard would change behavior.
     *
     * @var string[]
     */
    private const array TYPE_ASSERTION_FUNCTIONS = [
        'is_string',
        'is_int',
        'is_integer',
        'is_float',
        'is_double',
        'is_bool',
        'is_array',
        'is_object',
        'is_callable',
        'is_iterable',
        'is_numeric',
        'is_scalar',
        'is_countable',
        'is_null',
        'is_a',
    ];

    public function __construct(
        private readonly ExprAnalyzer $exprAnalyzer,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly SafeLeftTypeBooleanAndOrAnalyzer $safeLeftTypeBooleanAndOrAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove if condition that is always true', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function go()
    {
        if (1 === 1) {
            return 'yes';
        }

        return 'no';
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function go()
    {
        return 'yes';

        return 'no';
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
        return [If_::class];
    }

    /**
     * @param If_ $node
     * @return int|null|Stmt[]|If_
     */
    public function refactor(Node $node): int|null|array|If_
    {
        if ($node->cond instanceof BooleanAnd) {
            return $this->refactorIfWithBooleanAnd($node);
        }

        if ($node->else instanceof Else_) {
            return null;
        }

        // just one if
        if ($node->elseifs !== []) {
            return null;
        }

        $conditionStaticType = $this->nodeTypeResolver->getNativeType($node->cond);
        if (! $conditionStaticType->isTrue()->yes()) {
            return null;
        }

        if ($this->shouldSkipExpr($node->cond)) {
            return null;
        }

        if ($this->shouldSkipFromVariable($node->cond)) {
            return null;
        }

        $hasAssign = (bool) $this->betterNodeFinder->findFirstInstanceOf($node->cond, Assign::class);
        if ($hasAssign) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        $type = $scope->getNativeType($node->cond);
        if (! $type->isTrue()->yes()) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection && $classReflection->isTrait()) {
            return null;
        }

        if ($node->stmts === []) {
            return NodeVisitor::REMOVE_NODE;
        }

        // keep original comments
        if ($node->getComments() !== []) {
            $node->stmts[0]->setAttribute(AttributeKey::COMMENTS, array_merge(
                $node->getComments(),
                $node->stmts[0]->getComments(),
            ));
        }

        return $node->stmts;
    }

    private function shouldSkipFromVariable(Expr $expr): bool
    {
        /** @var Variable[] $variables */
        $variables = $this->betterNodeFinder->findInstancesOf($expr, [Variable::class]);

        foreach ($variables as $variable) {
            if ($this->exprAnalyzer->isNonTypedFromParam($variable)) {
                return true;
            }

            $type = $this->nodeTypeResolver->getNativeType($variable);
            if ($type instanceof IntersectionType) {
                foreach ($type->getTypes() as $subType) {
                    if ($subType->isArray()->yes()) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function shouldSkipExpr(Expr $expr): bool
    {
        $hasNonStaticNode = (bool) $this->betterNodeFinder->findInstancesOf(
            $expr,
            [
                PropertyFetch::class,
                StaticPropertyFetch::class,
                ArrayDimFetch::class,
                MethodCall::class,
                StaticCall::class,
            ]
        );

        if ($hasNonStaticNode) {
            return true;
        }

        /** @var FuncCall[] $funcCalls */
        $funcCalls = $this->betterNodeFinder->findInstancesOf($expr, [FuncCall::class]);
        foreach ($funcCalls as $funcCall) {
            if ($this->isNames($funcCall, self::RUNTIME_STATE_FUNCTIONS)) {
                return true;
            }
        }

        return false;
    }

    private function refactorIfWithBooleanAnd(If_ $if): ?If_
    {
        if (! $if->cond instanceof BooleanAnd) {
            return null;
        }

        $booleanAnd = $if->cond;

        $leftType = $this->getType($booleanAnd->left);
        if (! $leftType->isTrue()->yes()) {
            return null;
        }

        if (! $this->safeLeftTypeBooleanAndOrAnalyzer->isSafe($booleanAnd)) {
            return null;
        }

        // keep a type-assertion guard (e.g. is_string($x)) when the right operand reuses the same
        // variable: its "always true" type may be phpdoc-only and violated at runtime, so dropping
        // the guard would let the right operand run on an unexpected type
        if ($this->isReusedTypeGuard($booleanAnd)) {
            return null;
        }

        $if->cond = $booleanAnd->right;
        return $if;
    }

    private function isReusedTypeGuard(BooleanAnd $booleanAnd): bool
    {
        /** @var FuncCall[] $funcCalls */
        $funcCalls = $this->betterNodeFinder->findInstancesOf($booleanAnd->left, [FuncCall::class]);
        foreach ($funcCalls as $funcCall) {
            if (! $this->isNames($funcCall, self::TYPE_ASSERTION_FUNCTIONS)) {
                continue;
            }

            $args = $funcCall->getArgs();
            if (! isset($args[0])) {
                continue;
            }

            $subject = $args[0]->value;
            if (! $subject instanceof Variable) {
                continue;
            }

            $isReusedOnRight = (bool) $this->betterNodeFinder->findFirst(
                $booleanAnd->right,
                fn (Node $subNode): bool => $subNode instanceof Variable && $this->nodeComparator->areNodesEqual(
                    $subNode,
                    $subject
                )
            );

            if ($isReusedOnRight) {
                return true;
            }
        }

        return false;
    }
}
