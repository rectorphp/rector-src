<?php

declare(strict_types=1);

namespace Rector\Defluent\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Rector\Defluent\Matcher\AssignAndRootExprAndNodesToAddMatcher;
use Rector\Defluent\Skipper\FluentMethodCallSkipper;
use Rector\Defluent\ValueObject\AssignAndRootExprAndNodesToAdd;
use Rector\Defluent\ValueObject\FluentCallsKind;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Symfony\NodeAnalyzer\FluentNodeRemover;
use Symplify\PackageBuilder\Php\TypeChecker;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://ocramius.github.io/blog/fluent-interfaces-are-evil/
 *
 * @see \Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\FluentChainMethodCallToNormalMethodCallRectorTest
 * @see \Rector\Tests\Defluent\Rector\MethodCall\NewFluentChainMethodCallToNonFluentRector\NewFluentChainMethodCallToNonFluentRectorTest
 */
final class NewFluentChainMethodCallToNonFluentRector extends AbstractRector
{
    public function __construct(
        private TypeChecker $typeChecker,
        private FluentNodeRemover $fluentNodeRemover,
        private AssignAndRootExprAndNodesToAddMatcher $assignAndRootExprAndNodesToAddMatcher,
        private FluentMethodCallSkipper $fluentMethodCallSkipper
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Turns fluent interface calls to classic ones.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
(new SomeClass())->someFunction()
            ->otherFunction();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$someClass = new SomeClass();
$someClass->someFunction();
$someClass->otherFunction();
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        // handled by another rule
        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);

        // may happen if another rule make parent null
        if (! $parent instanceof Node) {
            return null;
        }

        if ($this->typeChecker->isInstanceOf($parent, [Return_::class, Arg::class])) {
            return null;
        }

        if (! $parent instanceof Assign) {
            return null;
        }

        $parentParent = $parent->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentParent instanceof Expression) {
            return null;
        }

        $statement = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $previous = $node->getAttribute(AttributeKey::PREVIOUS_NODE);

        if ($this->isFoundInPrevious($statement, $previous)) {
            return null;
        }

        if ($this->fluentMethodCallSkipper->shouldSkipRootMethodCall($node)) {
            return null;
        }

        $assignAndRootExprAndNodesToAdd = $this->assignAndRootExprAndNodesToAddMatcher->match(
            $node,
            FluentCallsKind::NORMAL
        );
        if (! $assignAndRootExprAndNodesToAdd instanceof AssignAndRootExprAndNodesToAdd) {
            return null;
        }

        $this->fluentNodeRemover->removeCurrentNode($node);
        $this->addNodesAfterNode($assignAndRootExprAndNodesToAdd->getNodesToAdd(), $node);

        return null;
    }

    private function isFoundInPrevious(Stmt $stmt, ?Node $previous): bool
    {
        if (! $previous instanceof Node) {
            return false;
        }

        $isFoundInPreviousAssign = (bool) $this->betterNodeFinder->findFirstPreviousOfNode($stmt, function (Node $node) use (
            $previous
        ): bool {
            if (! $node instanceof Assign) {
                return false;
            }

            return $this->nodeComparator->areNodesEqual($node->var, $previous);
        });

        if ($isFoundInPreviousAssign) {
            return true;
        }

        $previous = $previous->getAttribute(AttributeKey::PREVIOUS_NODE);
        return $this->isFoundInPrevious($stmt, $previous);
    }
}
