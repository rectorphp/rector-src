<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\If_\SimplifyIfIssetToNullCoalescingRector\SimplifyIfIssetToNullCoalescingRectorTest
 */
final class SimplifyIfIssetToNullCoalescingRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Simplify binary if to null coalesce', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeController
{
    public function run($possibleStatieYamlFile)
    {
        if (isset($possibleStatieYamlFile['import'])) {
            $possibleStatieYamlFile['import'] = array_merge($possibleStatieYamlFile['import'], $filesToImport);
        } else {
            $possibleStatieYamlFile['import'] = $filesToImport;
        }
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeController
{
    public function run($possibleStatieYamlFile)
    {
        $possibleStatieYamlFile['import'] = array_merge($possibleStatieYamlFile['import'] ?? [], $filesToImport);
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
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var Isset_ $issetNode */
        $issetNode = $node->cond;

        $valueNode = $issetNode->vars[0];

        // various scenarios
        $ifFirstStmt = $node->stmts[0];
        if (! $ifFirstStmt instanceof Expression) {
            return null;
        }

        $else = $node->else;
        if (! $else instanceof Else_) {
            return null;
        }

        $elseFirstStmt = $else->stmts[0];
        if (! $elseFirstStmt instanceof Expression) {
            return null;
        }

        /** @var Assign $firstAssign */
        $firstAssign = $ifFirstStmt->expr;

        /** @var Assign $secondAssign */
        $secondAssign = $elseFirstStmt->expr;

        // 1. array_merge
        if (! $firstAssign->expr instanceof FuncCall) {
            return null;
        }

        if (! $this->isName($firstAssign->expr, 'array_merge')) {
            return null;
        }

        if (! $this->nodeComparator->areNodesEqual($firstAssign->expr->args[0]->value, $valueNode)) {
            return null;
        }

        if (! $this->nodeComparator->areNodesEqual($secondAssign->expr, $firstAssign->expr->args[1]->value)) {
            return null;
        }

        $args = [new Arg(new Coalesce($valueNode, new Array_([]))), new Arg($secondAssign->expr)];
        $funcCall = new FuncCall(new Name('array_merge'), $args);

        return new Assign($valueNode, $funcCall);
    }

    private function shouldSkip(If_ $if): bool
    {
        if ($if->else === null) {
            return true;
        }

        if (count($if->elseifs) > 1) {
            return true;
        }

        if (! $if->cond instanceof Isset_) {
            return true;
        }

        if (! $this->hasOnlyStatementAssign($if)) {
            return true;
        }

        if (! $this->hasOnlyStatementAssign($if->else)) {
            return true;
        }

        $ifStmt = $if->stmts[0];
        if (! $ifStmt instanceof Expression) {
            return true;
        }

        if (! $ifStmt->expr instanceof Assign) {
            return true;
        }

        if (! $this->nodeComparator->areNodesEqual($if->cond->vars[0], $ifStmt->expr->var)) {
            return true;
        }

        $firstElseStmt = $if->else->stmts[0];
        if (! $firstElseStmt instanceof Expression) {
            return false;
        }

        if (! $firstElseStmt->expr instanceof Assign) {
            return false;
        }

        return ! $this->nodeComparator->areNodesEqual($if->cond->vars[0], $firstElseStmt->expr->var);
    }

    private function hasOnlyStatementAssign(If_ | Else_ $node): bool
    {
        if (count($node->stmts) !== 1) {
            return false;
        }

        if (! $node->stmts[0] instanceof Expression) {
            return false;
        }

        return $node->stmts[0]->expr instanceof Assign;
    }
}
