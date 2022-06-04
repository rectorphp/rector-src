<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\StmtsAwareInterface;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\While_;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\ValueObject\PropertyFetchToVariableAssign;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\ReadWrite\NodeFinder\NodeUsageFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustPropertyFetchRector\RemoveJustPropertyFetchRectorTest
 */
final class RemoveJustPropertyFetchRector extends AbstractRector
{
    public function __construct(
        private readonly NodeUsageFinder $nodeUsageFinder
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Inline property fetch assign to a variable, that has no added value', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private $name;

    public function run()
    {
        $name = $this->name;

        return $name;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private $name;

    public function run()
    {
        return $this->name;
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
        $stmts = (array) $node->stmts;
        if ($stmts === []) {
            return null;
        }

        $variableUsages = [];
        $currentStmtKey = null;

        $variableToPropertyAssign = null;

        foreach ($stmts as $key => $stmt) {
            $variableToPropertyAssign = $this->matchVariableToPropertyAssign($stmt);
            if (! $variableToPropertyAssign instanceof PropertyFetchToVariableAssign) {
                continue;
            }

            $assignPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($stmt);

            // there is a @var tag on purpose, keep the assign
            if ($assignPhpDocInfo->getVarTagValueNode() instanceof VarTagValueNode) {
                continue;
            }

            $followingStmts = array_slice($stmts, $key + 1);
            $variableUsages = $this->nodeUsageFinder->findVariableUsages(
                $followingStmts,
                $variableToPropertyAssign->getVariable()
            );

            $currentStmtKey = $key;

            // @todo validate the variable is not used in some place where property fetch cannot be used
            break;
        }

        // filter out variable usages that are part of nested property fetch, or change variable
        $variableUsages = $this->filterOutReferencedVariableUsages($variableUsages);

        if (! $variableToPropertyAssign instanceof PropertyFetchToVariableAssign) {
            return null;
        }

        if ($variableUsages === []) {
            return null;
        }

        /** @var int $currentStmtKey */
        return $this->replaceVariablesWithPropertyFetch(
            $node,
            $currentStmtKey,
            $variableUsages,
            $variableToPropertyAssign->getPropertyFetch()
        );
    }

    /**
     * @param Variable[] $variableUsages
     */
    private function replaceVariablesWithPropertyFetch(
        StmtsAwareInterface $stmtsAware,
        int $currentStmtsKey,
        array $variableUsages,
        PropertyFetch $propertyFetch
    ): StmtsAwareInterface {
        // remove assign node
        unset($stmtsAware->stmts[$currentStmtsKey]);

        $this->traverseNodesWithCallable(
            $stmtsAware,
            function (Node $node) use ($variableUsages, $propertyFetch): ?PropertyFetch {
                if (! in_array($node, $variableUsages, true)) {
                    return null;
                }

                return $propertyFetch;
            }
        );

        return $stmtsAware;
    }

    /**
     * @param Variable[] $variableUsages
     * @return Variable[]
     */
    private function filterOutReferencedVariableUsages(array $variableUsages): array
    {
        return array_filter($variableUsages, function (Variable $variable): bool {
            $variableUsageParent = $variable->getAttribute(AttributeKey::PARENT_NODE);
            if ($variableUsageParent instanceof Arg) {
                $variableUsageParent = $variableUsageParent->getAttribute(AttributeKey::PARENT_NODE);
            }

            // skip nested property fetch, the assign is for purpose of named variable
            if ($variableUsageParent instanceof PropertyFetch) {
                return false;
            }

            // skip, as assign can be used in a loop
            $parentWhile = $this->betterNodeFinder->findParentType($variable, While_::class);
            if ($parentWhile instanceof While_) {
                return false;
            }

            if (! $variableUsageParent instanceof FuncCall) {
                return true;
            }

            return ! $this->isName($variableUsageParent, 'array_pop');
        });
    }

    private function matchVariableToPropertyAssign(Stmt $stmt): ?PropertyFetchToVariableAssign
    {
        if (! $stmt instanceof Expression) {
            return null;
        }

        if (! $stmt->expr instanceof Assign) {
            return null;
        }

        $assign = $stmt->expr;
        if (! $assign->expr instanceof PropertyFetch) {
            return null;
        }

        // keep property fetch nesting
        if ($assign->expr->var instanceof PropertyFetch) {
            return null;
        }

        if (! $assign->var instanceof Variable) {
            return null;
        }

        return new PropertyFetchToVariableAssign($assign->var, $assign->expr);
    }
}
