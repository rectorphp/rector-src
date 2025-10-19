<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\Stmt;

use PhpParser\Node;
use PhpParser\Node\ContainsStmts;
use PhpParser\Node\Stmt;
use Rector\NodeAnalyzer\TerminatedNodeAnalyzer;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\RemoveUnreachableStatementRectorTest
 */
final class RemoveUnreachableStatementRector extends AbstractRector
{
    public function __construct(
        private readonly TerminatedNodeAnalyzer $terminatedNodeAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unreachable statements', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        return 5;

        $removeMe = 10;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        return 5;
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
        return [ContainsStmts::class];
    }

    /**
     * @param ContainsStmts $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->getStmts() === []) {
            return null;
        }

        // at least 2 items are needed
        if (count($node->getStmts()) < 2) {
            return null;
        }

        $originalStmts = $node->getStmts();
        $cleanedStmts = $this->processCleanUpUnreachableStmts($node, $node->getStmts());

        if ($cleanedStmts === $originalStmts) {
            return null;
        }

        $node->stmts = $cleanedStmts;
        return $node;
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    private function processCleanUpUnreachableStmts(ContainsStmts $containsStmts, array $stmts): array
    {
        foreach ($stmts as $key => $stmt) {
            if (! isset($stmts[$key - 1])) {
                continue;
            }

            $previousStmt = $stmts[$key - 1];

            // unset...

            if ($this->terminatedNodeAnalyzer->isAlwaysTerminated($containsStmts, $previousStmt, $stmt)) {
                array_splice($stmts, $key);
                return $stmts;
            }
        }

        return $stmts;
    }
}
