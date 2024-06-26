<?php

declare(strict_types=1);

namespace Rector\EarlyReturn\NodeFactory;

use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\EarlyReturn\NodeTransformer\ConditionInverter;
use Rector\NodeNestingScope\ContextAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @deprecated Since 1.1.2, as this rule creates inverted conditions and makes code much less readable.
 */
final readonly class InvertedIfFactory
{
    public function __construct(
        private ConditionInverter $conditionInverter,
        private ContextAnalyzer $contextAnalyzer
    ) {
    }

    /**
     * @param Expr[] $conditions
     * @return If_[]
     */
    public function createFromConditions(If_ $if, array $conditions, Return_ $return, ?Stmt $ifNextReturnStmt): array
    {
        $ifs = [];
        $stmt = $this->contextAnalyzer->isInLoop($if) && ! $ifNextReturnStmt instanceof Return_
            ? [new Continue_()]
            : [$return];

        if ($ifNextReturnStmt instanceof Return_) {
            $stmt[0]->setAttribute(AttributeKey::COMMENTS, $ifNextReturnStmt->getAttribute(AttributeKey::COMMENTS));
        }

        if ($ifNextReturnStmt instanceof Return_ && $ifNextReturnStmt->expr instanceof Expr) {
            $return->expr = $ifNextReturnStmt->expr;
        }

        foreach ($conditions as $condition) {
            $invertedCondition = $this->conditionInverter->createInvertedCondition($condition);
            $if = new If_($invertedCondition);
            $if->stmts = $stmt;
            $ifs[] = $if;
        }

        return $ifs;
    }
}
