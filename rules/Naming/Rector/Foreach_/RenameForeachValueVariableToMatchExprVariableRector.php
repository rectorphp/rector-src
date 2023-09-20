<?php

declare(strict_types=1);

namespace Rector\Naming\Rector\Foreach_;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Foreach_;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\Core\NodeManipulator\StmtsManipulator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\ExpectedNameResolver\InflectorSingularResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector\RenameForeachValueVariableToMatchExprVariableRectorTest
 */
final class RenameForeachValueVariableToMatchExprVariableRector extends AbstractRector
{
    public function __construct(
        private readonly InflectorSingularResolver $inflectorSingularResolver,
        private readonly PropertyFetchAnalyzer $propertyFetchAnalyzer,
        private readonly StmtsManipulator $stmtsManipulator,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Renames value variable name in foreach loop to match expression variable', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $array = [];
        foreach ($variables as $property) {
            $array[] = $property;
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
        $array = [];
        foreach ($variables as $variable) {
            $array[] = $variable;
        }
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

        $hasChanged = false;

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof Foreach_) {
                continue;
            }

            $isPropertyFetch = $this->propertyFetchAnalyzer->isLocalPropertyFetch($stmt->expr);
            if (! $stmt->expr instanceof Variable && ! $isPropertyFetch) {
                continue;
            }

            $exprName = $this->getName($stmt->expr);
            if ($exprName === null) {
                continue;
            }

            if ($stmt->keyVar instanceof Node) {
                continue;
            }

            $valueVarName = $this->getName($stmt->valueVar);
            if ($valueVarName === null) {
                continue;
            }

            $singularValueVarName = $this->inflectorSingularResolver->resolve($exprName);
            if ($singularValueVarName === $exprName) {
                continue;
            }

            if ($singularValueVarName === $valueVarName) {
                continue;
            }

            $alreadyUsedVariable = $this->betterNodeFinder->findVariableOfName($stmt->stmts, $singularValueVarName);
            if ($alreadyUsedVariable instanceof Variable) {
                continue;
            }

            if ($this->stmtsManipulator->isVariableUsedInNextStmt($node, $key + 1, $singularValueVarName)) {
                continue;
            }

            if ($this->stmtsManipulator->isVariableUsedInNextStmt($node, $key + 1, $valueVarName)) {
                continue;
            }

            $this->processRename($stmt, $valueVarName, $singularValueVarName);
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function processRename(Foreach_ $foreach, string $valueVarName, string $singularValueVarName): void
    {
        $foreach->valueVar = new Variable($singularValueVarName);
        $this->traverseNodesWithCallable($foreach->stmts, function (Node $node) use (
            $singularValueVarName,
            $valueVarName
        ): ?Variable {
            if (! $node instanceof Variable) {
                return null;
            }

            if (! $this->isName($node, $valueVarName)) {
                return null;
            }

            return new Variable($singularValueVarName);
        });
    }
}
