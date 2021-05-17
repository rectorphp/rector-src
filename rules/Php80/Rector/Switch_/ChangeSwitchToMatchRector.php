<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Switch_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\MatchArm;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Switch_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php80\NodeAnalyzer\SwitchAnalyzer;
use Rector\Php80\NodeResolver\SwitchExprsResolver;
use Rector\Php80\ValueObject\CondAndExpr;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/match_expression_v2
 * @see https://3v4l.org/572T5
 *
 * @see \Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\ChangeSwitchToMatchRectorTest
 */
final class ChangeSwitchToMatchRector extends AbstractRector
{
    private ?Expr $assignExpr = null;

    public function __construct(
        private SwitchExprsResolver $switchExprsResolver,
        private SwitchAnalyzer $switchAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change switch() to match()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        switch ($this->lexer->lookahead['type']) {
            case Lexer::T_SELECT:
                $statement = $this->SelectStatement();
                break;

            case Lexer::T_UPDATE:
                $statement = $this->UpdateStatement();
                break;

            default:
                $statement = $this->syntaxError('SELECT, UPDATE or DELETE');
                break;
        }
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $statement = match ($this->lexer->lookahead['type']) {
            Lexer::T_SELECT => $this->SelectStatement(),
            Lexer::T_UPDATE => $this->UpdateStatement(),
            default => $this->syntaxError('SELECT, UPDATE or DELETE'),
        };
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
        return [Switch_::class];
    }

    /**
     * @param Switch_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipSwitch($node)) {
            return null;
        }

        $condAndExprs = $this->switchExprsResolver->resolve($node);
        if ($condAndExprs === []) {
            return null;
        }

        if (! $this->haveCondAndExprsMatchPotential($condAndExprs)) {
            return null;
        }

        $this->assignExpr = null;

        $isReturn = false;
        foreach ($condAndExprs as $condAndExpr) {
            if ($condAndExpr->getKind() === CondAndExpr::TYPE_RETURN) {
                $isReturn = true;
                break;
            }

            $expr = $condAndExpr->getExpr();
            if (! $expr instanceof Assign) {
                return null;
            }
        }

        $matchArms = $this->createMatchArmsFromCases($condAndExprs);
        $match = new Match_($node->cond, $matchArms);

        if ($isReturn) {
            return new Return_($match);
        }

        if ($this->assignExpr) {
            /** @var Expr $assignExpr */
            $assignExpr = $this->assignExpr;
            return $this->changeToAssign($node, $match, $assignExpr);
        }

        return $match;
    }

    private function changeToAssign(Switch_ $switch, Match_ $match, Expr $assignExpr): Assign
    {
        $prevInitializedAssign = $this->betterNodeFinder->findFirstPreviousOfNode(
            $switch,
            fn (Node $node): bool => $node instanceof Assign && $this->nodeComparator->areNodesEqual($node->var, $assignExpr)
        );

        $assign = new Assign($assignExpr, $match);
        if ($prevInitializedAssign instanceof Assign) {
            /** @var Match_ $expr */
            $expr = $assign->expr;
            $expr->arms[] = new MatchArm(null, $prevInitializedAssign->expr);

            $parentAssign = $prevInitializedAssign->getAttribute(AttributeKey::PARENT_NODE);
            if ($parentAssign instanceof Expression) {
                $this->removeNode($parentAssign);
            }
        }

        return $assign;
    }

    private function shouldSkipSwitch(Switch_ $switch): bool
    {
        if (! $this->switchAnalyzer->hasEachCaseBreak($switch)) {
            return true;
        }

        return ! $this->switchAnalyzer->hasEachCaseSingleStmt($switch);
    }

    /**
     * @param CondAndExpr[] $condAndExprs
     */
    private function haveCondAndExprsMatchPotential(array $condAndExprs): bool
    {
        $uniqueCondAndExprKinds = $this->resolveUniqueKinds($condAndExprs);
        if (count($uniqueCondAndExprKinds) > 1) {
            return false;
        }

        $assignVariableNames = [];
        foreach ($condAndExprs as $condAndExpr) {
            $expr = $condAndExpr->getExpr();
            if (! $expr instanceof Assign) {
                continue;
            }

            $assignVariableNames[] = $this->getName($expr->var);
        }

        $assignVariableNames = array_unique($assignVariableNames);
        return count($assignVariableNames) <= 1;
    }

    /**
     * @param CondAndExpr[] $condAndExprs
     * @return MatchArm[]
     */
    private function createMatchArmsFromCases(array $condAndExprs): array
    {
        $matchArms = [];
        foreach ($condAndExprs as $condAndExpr) {
            $expr = $condAndExpr->getExpr();

            if ($expr instanceof Assign) {
                $this->assignExpr = $expr->var;
                $expr = $expr->expr;
            }

            $condExpr = $condAndExpr->getCondExpr();

            $condList = $condExpr instanceof Expr ? [$condExpr] : null;
            $matchArms[] = new MatchArm($condList, $expr);
        }

        return $matchArms;
    }

    /**
     * @param CondAndExpr[] $condAndExprs
     * @return string[]
     */
    private function resolveUniqueKinds(array $condAndExprs): array
    {
        $condAndExprKinds = [];
        foreach ($condAndExprs as $condAndExpr) {
            $condAndExprKinds[] = $condAndExpr->getKind();
        }

        return array_unique($condAndExprKinds);
    }
}
