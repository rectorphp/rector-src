<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\Assign;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PHPStan\Analyser\Scope;
use Rector\Core\Php\ReservedKeywordAnalyzer;
use Rector\Core\PhpParser\Comparing\ConditionSearcher;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\DeadCode\NodeAnalyzer\ExprUsedInNextNodeAnalyzer;
use Rector\DeadCode\NodeAnalyzer\UsedVariableNameAnalyzer;
use Rector\DeadCode\SideEffect\SideEffectNodeDetector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php74\Tokenizer\FollowedByCurlyBracketAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\RemoveUnusedVariableAssignRectorTest
 */
final class RemoveUnusedVariableAssignRector extends AbstractScopeAwareRector
{
    public function __construct(
        private readonly ReservedKeywordAnalyzer $reservedKeywordAnalyzer,
        private readonly ConditionSearcher $conditionSearcher,
        private readonly UsedVariableNameAnalyzer $usedVariableNameAnalyzer,
        private readonly SideEffectNodeDetector $sideEffectNodeDetector,
        private readonly ExprUsedInNextNodeAnalyzer $exprUsedInNextNodeAnalyzer,
        private readonly FollowedByCurlyBracketAnalyzer $followedByCurlyBracketAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused assigns to variables', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $value = 5;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
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
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $variable = $node->var;
        if (! $variable instanceof Variable) {
            return null;
        }

        $variableName = $this->getName($variable);
        if ($variableName !== null && $this->reservedKeywordAnalyzer->isNativeVariable($variableName)) {
            return null;
        }

        // variable is used
        if ($this->isUsed($node, $variable, $scope)) {
            return $this->refactorUsedVariable($node, $scope);
        }

        if ($this->hasCallLikeInAssignExpr($node->expr)) {
            // keep the expr, can have side effect
            return $this->cleanCastedExpr($node->expr);
        }

        $this->removeNode($node);
        return null;
    }

    private function cleanCastedExpr(Expr $expr): Expr
    {
        if (! $expr instanceof Cast) {
            return $expr;
        }

        $castedExpr = $expr->expr;
        return $this->cleanCastedExpr($castedExpr);
    }

    private function hasCallLikeInAssignExpr(Expr $expr): bool
    {
        return (bool) $this->betterNodeFinder->findFirst(
            $expr,
            fn (Node $subNode): bool => $this->sideEffectNodeDetector->detectCallExpr($subNode)
        );
    }

    private function shouldSkip(Assign $assign): bool
    {
        $classMethod = $this->betterNodeFinder->findParentType($assign, ClassMethod::class);
        if (! $classMethod instanceof FunctionLike) {
            return true;
        }

        $variable = $assign->var;
        if (! $variable instanceof Variable) {
            return true;
        }

        $parentNode = $assign->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentNode instanceof Expression) {
            return true;
        }

        $originalNode = $parentNode->getAttribute(AttributeKey::ORIGINAL_NODE);
        if (! $originalNode instanceof Node) {
            return true;
        }

        if (! $variable->name instanceof Variable) {
            return $this->followedByCurlyBracketAnalyzer->isFollowed($this->file, $variable);
        }

        return (bool) $this->betterNodeFinder->findFirstNext(
            $assign,
            static fn (Node $node): bool => $node instanceof Variable
        );
    }

    private function isUsed(Assign $assign, Variable $variable, Scope $scope): bool
    {
        $isUsedPrev = $scope->hasVariableType((string) $this->getName($variable))
            ->yes();

        if ($isUsedPrev) {
            return true;
        }

        if ($this->exprUsedInNextNodeAnalyzer->isUsed($variable)) {
            return true;
        }

        /** @var FuncCall|MethodCall|New_|NullsafeMethodCall|StaticCall $expr */
        $expr = $assign->expr;
        if (! $this->sideEffectNodeDetector->detectCallExpr($expr)) {
            return false;
        }

        return $this->isUsedInAssignExpr($expr, $assign, $scope);
    }

    private function isUsedInAssignExpr(CallLike | Expr $expr, Assign $assign, Scope $scope): bool
    {
        if (! $expr instanceof CallLike) {
            return $this->isUsedInPreviousAssign($assign, $expr, $scope);
        }

        if ($expr->isFirstClassCallable()) {
            return false;
        }

        foreach ($expr->getArgs() as $arg) {
            $variable = $arg->value;

            if ($this->isUsedInPreviousAssign($assign, $variable, $scope)) {
                return true;
            }
        }

        return false;
    }

    private function isUsedInPreviousAssign(Assign $assign, Expr $expr, Scope $scope): bool
    {
        if (! $expr instanceof Variable) {
            return false;
        }

        $previousAssign = $this->betterNodeFinder->findFirstPrevious(
            $assign,
            fn (Node $node): bool => $node instanceof Assign && $this->usedVariableNameAnalyzer->isVariableNamed(
                $node->var,
                $expr
            )
        );

        if ($previousAssign instanceof Assign) {
            return $this->isUsed($assign, $expr, $scope);
        }

        return false;
    }

    private function refactorUsedVariable(Assign $assign, Scope $scope): null|Expr
    {
        $parentNode = $assign->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentNode instanceof Expression) {
            return null;
        }

        $if = $parentNode->getAttribute(AttributeKey::NEXT_NODE);

        // check if next node is if
        if (! $if instanceof If_) {
            if (
                $assign->var instanceof Variable &&
                ! $scope->hasVariableType((string) $this->getName($assign->var))
                    ->yes() &&
                ! $this->exprUsedInNextNodeAnalyzer->isUsed($assign->var)) {
                return $this->cleanCastedExpr($assign->expr);
            }

            return null;
        }

        if ($this->conditionSearcher->hasIfAndElseForVariableRedeclaration($assign, $if)) {
            $this->removeNode($assign);
            return $assign;
        }

        return null;
    }
}
