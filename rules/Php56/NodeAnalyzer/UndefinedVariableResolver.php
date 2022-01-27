<?php

declare(strict_types=1);

namespace Rector\Php56\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\Cast\Unset_ as UnsetCast;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Global_;
use PhpParser\Node\Stmt\Static_;
use PhpParser\Node\Stmt\StaticVar;
use PhpParser\Node\Stmt\Unset_;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\Scope;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class UndefinedVariableResolver
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeComparator $nodeComparator,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @return string[]
     */
    public function resolve(ClassMethod | Function_ | Closure $node): array
    {
        $undefinedVariables = [];

        $variableNamesFromParams = $this->collectVariableNamesFromParams($node);
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable((array) $node->stmts, function (Node $node) use (
            &$undefinedVariables,
            $variableNamesFromParams
        ): ?int {
            // entering new scope - break!
            if ($node instanceof FunctionLike && ! $node instanceof ArrowFunction) {
                return NodeTraverser::STOP_TRAVERSAL;
            }

            if ($node instanceof Foreach_) {
                // handled above
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if (! $node instanceof Variable) {
                return null;
            }

            if ($this->shouldSkipVariable($node)) {
                return null;
            }

            $variableName = $this->nodeNameResolver->getName($node);
            if (! is_string($variableName)) {
                return null;
            }

            // defined 100 %
            /** @var Scope $scope */
            $scope = $node->getAttribute(AttributeKey::SCOPE);
            if ($scope->hasVariableType($variableName)->yes()) {
                return null;
            }

            if (in_array($variableName, $variableNamesFromParams, true)) {
                return null;
            }

            $undefinedVariables[] = $variableName;

            return null;
        });

        return array_unique($undefinedVariables);
    }

    /**
     * @return string[]
     */
    private function collectVariableNamesFromParams(ClassMethod | Function_ | Closure $node): array
    {
        $variableNames = [];
        foreach ($node->getParams() as $param) {
            if ($param->var instanceof Variable) {
                $variableNames[] = (string) $this->nodeNameResolver->getName($param->var);
            }
        }

        return $variableNames;
    }

    private function issetOrUnsetOrEmptyParent(Node $parentNode): bool
    {
        return in_array($parentNode::class, [Unset_::class, UnsetCast::class, Isset_::class, Empty_::class], true);
    }

    private function isAsCoalesceLeft(Node $parentNode, Variable $variable): bool
    {
        return $parentNode instanceof Coalesce && $parentNode->left === $variable;
    }

    private function isAssignOrStaticVariableParent(Node $parentNode): bool
    {
        if (in_array($parentNode::class, [Assign::class, AssignRef::class], true)) {
            return true;
        }

        return $this->isStaticVariable($parentNode);
    }

    private function shouldSkipVariable(Variable $variable): bool
    {
        $parentNode = $variable->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentNode instanceof Node) {
            return true;
        }

        if ($parentNode instanceof Global_) {
            return true;
        }

        if ($this->isAssignOrStaticVariableParent($parentNode)) {
            return true;
        }

        if ($this->issetOrUnsetOrEmptyParent($parentNode)) {
            return true;
        }

        if ($this->isAsCoalesceLeft($parentNode, $variable)) {
            return true;
        }

        // list() = | [$values] = defines variables as null
        if ($this->isListAssign($parentNode)) {
            return true;
        }

        if ($this->isDifferentWithOriginalNodeOrNoScope($variable)) {
            return true;
        }

        $variableName = $this->nodeNameResolver->getName($variable);

        // skip $this, as probably in outer scope
        if ($variableName === 'this') {
            return true;
        }

        if ($variableName === null) {
            return true;
        }

        if ($this->hasPreviousCheckedWithIsset($variable)) {
            return true;
        }

        return $this->hasPreviousCheckedWithEmpty($variable);
    }

    private function isDifferentWithOriginalNodeOrNoScope(Variable $variable): bool
    {
        $originalNode = $variable->getAttribute(AttributeKey::ORIGINAL_NODE);
        if (! $this->nodeComparator->areNodesEqual($variable, $originalNode)) {
            return true;
        }

        $nodeScope = $variable->getAttribute(AttributeKey::SCOPE);
        return ! $nodeScope instanceof Scope;
    }

    private function hasPreviousCheckedWithIsset(Variable $variable): bool
    {
        return (bool) $this->betterNodeFinder->findFirstPreviousOfNode($variable, function (Node $subNode) use (
            $variable
        ): bool {
            if (! $subNode instanceof Isset_) {
                return false;
            }

            $vars = $subNode->vars;
            foreach ($vars as $var) {
                if ($this->nodeComparator->areNodesEqual($variable, $var)) {
                    return true;
                }
            }

            return false;
        });
    }

    private function hasPreviousCheckedWithEmpty(Variable $variable): bool
    {
        return (bool) $this->betterNodeFinder->findFirstPreviousOfNode($variable, function (Node $subNode) use (
            $variable
        ): bool {
            if (! $subNode instanceof Empty_) {
                return false;
            }

            $subNodeExpr = $subNode->expr;
            return $this->nodeComparator->areNodesEqual($subNodeExpr, $variable);
        });
    }

    private function isStaticVariable(Node $parentNode): bool
    {
        if (! $parentNode instanceof StaticVar) {
            return false;
        }

        // definition of static variable
        $parentParentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);
        return $parentParentNode instanceof Static_;
    }

    private function isListAssign(Node $node): bool
    {
        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
        if ($parentNode instanceof List_) {
            return true;
        }

        return $parentNode instanceof Array_;
    }
}
