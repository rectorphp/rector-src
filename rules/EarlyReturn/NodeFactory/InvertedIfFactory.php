<?php

declare(strict_types=1);

namespace Rector\EarlyReturn\NodeFactory;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\EarlyReturn\NodeTransformer\ConditionInverter;
use Rector\NodeNestingScope\ContextAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class InvertedIfFactory
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ConditionInverter $conditionInverter,
        private ContextAnalyzer $contextAnalyzer
    ) {
    }

    /**
     * @param Expr[] $conditions
     * @return If_[]
     */
    public function createFromConditions(If_ $if, array $conditions, Return_ $return): array
    {
        $ifs = [];
        $ifNextReturn = $this->getIfNextReturn($if);
        $stmt = $this->contextAnalyzer->isInLoop($if) && ! $ifNextReturn
            ? [new Continue_()]
            : [$return];

        if ($ifNextReturn instanceof Return_) {
            $stmt[0]->setAttribute(AttributeKey::COMMENTS, $ifNextReturn->getAttribute(AttributeKey::COMMENTS));
        }

        $getNextReturnExpr = $this->getNextReturnExpr($if);
        if ($getNextReturnExpr instanceof Return_) {
            $return->expr = $getNextReturnExpr->expr;
        }

        foreach ($conditions as $condition) {
            $invertedCondition = $this->conditionInverter->createInvertedCondition($condition);
            $if = new If_($invertedCondition);
            $if->stmts = $stmt;
            $ifs[] = $if;
        }

        return $ifs;
    }

    private function getNextReturnExpr(If_ $if): ?Node
    {
        return $this->betterNodeFinder->findFirstNext(
            $if,
            fn (Node $node): bool => $node instanceof Return_ && $node->expr instanceof Expr
        );
    }

    private function getIfNextReturn(If_ $if): ?Return_
    {
        $nextNode = $if->getAttribute(AttributeKey::NEXT_NODE);
        if (! $nextNode instanceof Return_) {
            return null;
        }

        return $nextNode;
    }
}
