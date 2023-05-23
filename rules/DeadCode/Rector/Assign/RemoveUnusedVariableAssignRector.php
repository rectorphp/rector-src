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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?ClassMethod
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            $assign = $stmt->expr;
            if ($this->shouldSkipAssign($assign)) {
                continue;
            }

            if (! $assign->var instanceof Variable) {
                return null;
            }

            $nextStmt = $node->stmts[$key + 1] ?? null;

            $variableName = $this->getName($assign->var);
            if ($variableName !== null && $this->reservedKeywordAnalyzer->isNativeVariable($variableName)) {
                return null;
            }

            // variable is used
            if ($this->isUsed($assign, $assign->var, $scope)) {
                $shouldRemove = $this->refactorUsedVariable($assign, $scope, $nextStmt);
                if ($shouldRemove === true) {
                    unset($node->stmts[$key]);
                    return $node;
                }

                if ($shouldRemove instanceof \PhpParser\Node) {
                    $node->stmts[$key] = new Expression($shouldRemove);
                    return $node;
                }
            }

            if ($this->hasCallLikeInAssignExpr($assign->expr, $scope)) {
                // keep the expr, can have side effect
                $cleanedExpr = $this->cleanCastedExpr($assign->expr);
                $node->stmts[$key] = new Expression($cleanedExpr);

                return $node;
            }

            unset($node->stmts[$key]);
            return $node;
        }

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

    private function hasCallLikeInAssignExpr(Expr $expr, Scope $scope): bool
    {
        return (bool) $this->betterNodeFinder->findFirst(
            $expr,
            fn (Node $subNode): bool => $this->sideEffectNodeDetector->detectCallExpr($subNode, $scope)
        );
    }

    private function shouldSkipAssign(Assign $assign): bool
    {
        $variable = $assign->var;
        if (! $variable instanceof Variable) {
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
        if (! $this->sideEffectNodeDetector->detectCallExpr($expr, $scope)) {
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

    private function refactorUsedVariable(Assign $assign, Scope $scope, ?Node\Stmt $nextStmt): bool|null|Expr
    {
        // check if next node is if
        if (! $nextStmt instanceof If_) {
            if (
                $assign->var instanceof Variable &&
                ! $scope->hasVariableType((string) $this->getName($assign->var))
                    ->yes() &&
                ! $this->exprUsedInNextNodeAnalyzer->isUsed($assign->var)) {
                return $this->cleanCastedExpr($assign->expr);
            }

            return null;
        }

        if ($this->conditionSearcher->hasIfAndElseForVariableRedeclaration($assign, $nextStmt)) {
            return true;
        }

        return null;
    }
}
