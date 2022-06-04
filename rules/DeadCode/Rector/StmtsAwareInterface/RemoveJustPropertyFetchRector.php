<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\StmtsAwareInterface;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Rector\AbstractRector;
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
        private NodeUsageFinder $nodeUsageFinder
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
        $propertyFetch = null;
        $currentStmtsKey = null;

        foreach ($stmts as $key => $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            // assign to a variable
            // ...
            $assign = $stmt->expr;

            if (! $assign->expr instanceof PropertyFetch) {
                continue;
            }

            if (! $assign->var instanceof Variable) {
                continue;
            }

            $assignPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($stmt);

            // there is a @var tag on purpose, keep the assign
            if ($assignPhpDocInfo->getVarTagValueNode() instanceof VarTagValueNode) {
                continue;
            }

            $currentVariable = $assign->var;
            $propertyFetch = $assign->expr;

            $followingStmts = array_slice($stmts, $key + 1);
            $variableUsages = $this->nodeUsageFinder->findVariableUsages($followingStmts, $currentVariable);

            $currentStmtsKey = $key;

            // @todo validate the variable is not used in some place where property fetch cannot be used
            break;
        }

        // filter out variable usages that are part of nested property fetch, or change variable
        $variableUsages = $this->filterOutReferencedVariableUsages($variableUsages);

        if ($variableUsages === [] || $propertyFetch === null) {
            return null;
        }

        /** @var int $currentStmtsKey */
        return $this->replaceVariablesWithPropertyFetch($node, $currentStmtsKey, $variableUsages, $propertyFetch);
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

        $this->traverseNodesWithCallable($stmtsAware, function (Node $node) use ($variableUsages, $propertyFetch) {
            if (! in_array($node, $variableUsages, true)) {
                return null;
            }

            return $propertyFetch;
        });

        return $stmtsAware;
    }

    /**
     * @param Variable[] $variableUsages
     * @return Variable[]
     */
    private function filterOutReferencedVariableUsages(array $variableUsages): array
    {
        return array_filter($variableUsages, function (Variable $variable) {
            $variableUsageParent = $variable->getAttribute(AttributeKey::PARENT_NODE);
            if ($variableUsageParent instanceof Arg) {
                $variableUsageParent = $variableUsageParent->getAttribute(AttributeKey::PARENT_NODE);
            }

            // skip nested property fetch, the assign is for purpose of named variable
            if ($variableUsageParent instanceof PropertyFetch) {
                return false;
            }

            if ($variableUsageParent instanceof FuncCall) {
                if ($this->isName($variableUsageParent, 'array_pop')) {
                    return false;
                }
            }

            return true;
        });
    }
}
