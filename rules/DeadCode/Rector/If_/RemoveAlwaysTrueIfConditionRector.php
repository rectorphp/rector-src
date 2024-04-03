<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeTraverser;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Constant\ConstantBooleanType;
use Rector\DeadCode\NodeAnalyzer\SafeLeftTypeBooleanAndOrAnalyzer;
use Rector\NodeAnalyzer\ExprAnalyzer;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\RemoveAlwaysTrueIfConditionRectorTest
 */
final class RemoveAlwaysTrueIfConditionRector extends AbstractRector
{
    /**
     * @var int[]
     */
    private array $callsEndTokens = [];

    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
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

    public function beforeTraverse(array $nodes): ?array
    {
        $this->callsEndTokens = [];

        $calls = $this->betterNodeFinder->find(
            $nodes,
            static fn (Node $node): bool => $node instanceof MethodCall || $node instanceof StaticCall
        );

        foreach ($calls as $call) {
            $tokenEnd = $call->getEndTokenPos();
            if ($tokenEnd > 0) {
                $this->callsEndTokens[] = $tokenEnd;
            }
        }

        return parent::beforeTraverse($nodes);
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

        $conditionStaticType = $this->getType($node->cond);
        if (! $conditionStaticType instanceof ConstantBooleanType) {
            return null;
        }

        if (! $conditionStaticType->getValue()) {
            return null;
        }

        if ($this->shouldSkipPropertyFetch($node->cond)) {
            return null;
        }

        if ($this->shouldSkipFromParam($node->cond)) {
            return null;
        }

        $hasAssign = (bool) $this->betterNodeFinder->findFirstInstanceOf($node->cond, Assign::class);
        if ($hasAssign) {
            return null;
        }

        if ($node->stmts === []) {
            return NodeTraverser::REMOVE_NODE;
        }

        return $node->stmts;
    }

    private function shouldSkipFromParam(Expr $expr): bool
    {
        /** @var Variable[] $variables */
        $variables = $this->betterNodeFinder->findInstancesOf($expr, [Variable::class]);

        foreach ($variables as $variable) {
            if ($this->exprAnalyzer->isNonTypedFromParam($variable)) {
                return true;
            }
        }

        return false;
    }

    private function shouldSkipPropertyFetch(Expr $expr): bool
    {
        /** @var PropertyFetch[]|StaticPropertyFetch[] $propertyFetches */
        $propertyFetches = $this->betterNodeFinder->findInstancesOf(
            $expr,
            [PropertyFetch::class, StaticPropertyFetch::class]
        );

        foreach ($propertyFetches as $propertyFetch) {
            $classReflection = $this->reflectionResolver->resolveClassReflectionSourceObject($propertyFetch);

            if (! $classReflection instanceof ClassReflection) {
                // cannot get parent Trait_ from Property Fetch
                return true;
            }

            $propertyName = (string) $this->nodeNameResolver->getName($propertyFetch);

            if (! $classReflection->hasNativeProperty($propertyName)) {
                continue;
            }

            $nativeProperty = $classReflection->getNativeProperty($propertyName);
            if (! $nativeProperty->hasNativeType()) {
                return true;
            }

            $startTokenPos = $propertyFetch->getStartTokenPos();
            foreach ($this->callsEndTokens as $callEndToken) {
                if ($startTokenPos > $callEndToken) {
                    return true;
                }
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
        if (! $leftType instanceof ConstantBooleanType) {
            return null;
        }

        if (! $leftType->getValue()) {
            return null;
        }

        if (! $this->safeLeftTypeBooleanAndOrAnalyzer->isSafe($booleanAnd)) {
            return null;
        }

        $if->cond = $booleanAnd->right;
        return $if;
    }
}
