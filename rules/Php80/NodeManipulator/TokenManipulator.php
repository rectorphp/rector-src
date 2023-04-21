<?php

declare(strict_types=1);

namespace Rector\Php80\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\If_;
use Rector\Core\NodeAnalyzer\ArgsAnalyzer;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\Core\Util\StringUtils;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\Php80\ValueObject\ArrayDimFetchAndConstFetch;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PostRector\Collector\NodesToRemoveCollector;

final class TokenManipulator
{
    private ?Expr $assignedNameExpr = null;

    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly NodesToRemoveCollector $nodesToRemoveCollector,
        private readonly ValueResolver $valueResolver,
        private readonly NodeComparator $nodeComparator,
        private readonly ArgsAnalyzer $argsAnalyzer,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @param Node[] $nodes
     */
    public function refactorArrayToken(array $nodes, Variable $singleTokenVariable): void
    {
        $this->replaceTokenDimFetchZeroWithGetTokenName($nodes, $singleTokenVariable);

        // replace "$token[1]"; with "$token->value"
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, function (Node $node): ?PropertyFetch {
            if (! $node instanceof ArrayDimFetch) {
                return null;
            }

            if (! $this->isArrayDimFetchWithDimIntegerValue($node, 1)) {
                return null;
            }

            $tokenStaticType = $this->nodeTypeResolver->getType($node->var);
            if (! $tokenStaticType->isArray()->yes()) {
                return null;
            }

            return new PropertyFetch($node->var, 'text');
        });
    }

    /**
     * @param Node[] $nodes
     */
    public function refactorNonArrayToken(array $nodes, Variable $singleTokenVariable): void
    {
        // replace "$content = $token;" → "$content = $token->text;"
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, function (Node $node) use (
            $singleTokenVariable
        ) {
            if (! $node instanceof Assign) {
                return null;
            }

            if (! $this->nodeComparator->areNodesEqual($node->expr, $singleTokenVariable)) {
                return null;
            }

            $tokenStaticType = $this->nodeTypeResolver->getType($node->expr);
            if ($tokenStaticType->isArray()->yes()) {
                return null;
            }

            $node->expr = new PropertyFetch($singleTokenVariable, 'text');
        });

        // replace "$name = null;" → "$name = $token->getTokenName();"
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, function (Node $node) use (
            $singleTokenVariable
        ): ?Assign {
            if (! $node instanceof Assign) {
                return null;
            }

            $tokenStaticType = $this->nodeTypeResolver->getType($node->expr);
            if ($tokenStaticType->isArray()->yes()) {
                return null;
            }

            if (! $this->assignedNameExpr instanceof Expr) {
                return null;
            }

            if (! $this->nodeComparator->areNodesEqual($node->var, $this->assignedNameExpr)) {
                return null;
            }

            if (! $this->valueResolver->isValue($node->expr, 'null')) {
                return null;
            }

            $node->expr = new MethodCall($singleTokenVariable, 'getTokenName');

            return $node;
        });
    }

    /**
     * @param Node[] $nodes
     */
    public function refactorTokenIsKind(array $nodes, Variable $singleTokenVariable): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, function (Node $node) use (
            $singleTokenVariable
        ): null|MethodCall|BooleanNot {
            if (! $node instanceof Identical && ! $node instanceof NotIdentical) {
                return null;
            }

            $arrayDimFetchAndConstFetch = $this->matchArrayDimFetchAndConstFetch($node);
            if (! $arrayDimFetchAndConstFetch instanceof ArrayDimFetchAndConstFetch) {
                return null;
            }

            if (! $this->isArrayDimFetchWithDimIntegerValue($arrayDimFetchAndConstFetch->getArrayDimFetch(), 0)) {
                return null;
            }

            $arrayDimFetch = $arrayDimFetchAndConstFetch->getArrayDimFetch();
            $constFetch = $arrayDimFetchAndConstFetch->getConstFetch();

            if (! $this->nodeComparator->areNodesEqual($arrayDimFetch->var, $singleTokenVariable)) {
                return null;
            }

            $constName = $this->nodeNameResolver->getName($constFetch);
            if ($constName === null) {
                return null;
            }

            if (! StringUtils::isMatch($constName, '#^T_#')) {
                return null;
            }

            $isTConstTypeMethodCall = $this->createIsTConstTypeMethodCall(
                $arrayDimFetch,
                $arrayDimFetchAndConstFetch->getConstFetch()
            );

            if ($node instanceof Identical) {
                return $isTConstTypeMethodCall;
            }

            return new BooleanNot($isTConstTypeMethodCall);
        });
    }

    /**
     * @param Node[] $nodes
     */
    public function removeIsArray(array $nodes, Variable $singleTokenVariable): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, function (Node $node) use (
            $singleTokenVariable
        ): ?FuncCall {
            if (! $node instanceof FuncCall) {
                return null;
            }

            if (! $this->nodeNameResolver->isName($node, 'is_array')) {
                return null;
            }

            if (! $this->argsAnalyzer->isArgInstanceInArgsPosition($node->args, 0)) {
                return null;
            }

            /** @var Arg $firstArg */
            $firstArg = $node->args[0];
            if (! $this->nodeComparator->areNodesEqual($firstArg->value, $singleTokenVariable)) {
                return null;
            }

            if ($this->shouldSkipNodeRemovalForPartOfIf($node)) {
                return null;
            }

            // remove correct node
            $nodeToRemove = $this->matchParentNodeInCaseOfIdenticalTrue($node);

            $parentNode = $nodeToRemove->getAttribute(AttributeKey::PARENT_NODE);

            if ($parentNode instanceof Ternary) {
                $this->replaceTernary($parentNode);
                return $node;
            }

            if (! $parentNode instanceof Node) {
                return null;
            }

            if ($parentNode instanceof BooleanNot) {
                $parentOfParentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);
                if ($parentOfParentNode instanceof BinaryOp) {
                    $this->nodesToRemoveCollector->addNodeToRemove($parentNode);
                    return $node;
                }
            }

            $this->nodesToRemoveCollector->addNodeToRemove($nodeToRemove);
            return $node;
        });
    }

    private function replaceTernary(Ternary $ternary): void
    {
        $currentStmt = $this->betterNodeFinder->resolveCurrentStatement($ternary);

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $currentStmt,
            static function (Node $subNode) use ($ternary): ?Expr {
                if ($subNode === $ternary) {
                    return $ternary->if;
                }

                return null;
            }
        );
    }

    /**
     * Replace $token[0] with $token->getTokenName() call
     *
     * @param Node[] $nodes
     */
    private function replaceTokenDimFetchZeroWithGetTokenName(array $nodes, Variable $singleTokenVariable): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, function (Node $node) use (
            $singleTokenVariable
        ): ?MethodCall {
            if (! $node instanceof FuncCall) {
                return null;
            }

            if (! $this->nodeNameResolver->isName($node, 'token_name')) {
                return null;
            }

            if (! $this->argsAnalyzer->isArgInstanceInArgsPosition($node->args, 0)) {
                return null;
            }

            /** @var Arg $firstArg */
            $firstArg = $node->args[0];
            $possibleTokenArray = $firstArg->value;
            if (! $possibleTokenArray instanceof ArrayDimFetch) {
                return null;
            }

            $tokenStaticType = $this->nodeTypeResolver->getType($possibleTokenArray->var);
            if (! $tokenStaticType->isArray()->yes()) {
                return null;
            }

            if (! $possibleTokenArray->dim instanceof Expr) {
                return null;
            }

            if (! $this->valueResolver->isValue($possibleTokenArray->dim, 0)) {
                return null;
            }

            if (! $this->nodeComparator->areNodesEqual($possibleTokenArray->var, $singleTokenVariable)) {
                return null;
            }

            // save token variable name for later
            $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
            if ($parentNode instanceof Assign) {
                $this->assignedNameExpr = $parentNode->var;
            }

            return new MethodCall($singleTokenVariable, 'getTokenName');
        });
    }

    private function isArrayDimFetchWithDimIntegerValue(ArrayDimFetch $arrayDimFetch, int $value): bool
    {
        if (! $arrayDimFetch->dim instanceof Expr) {
            return false;
        }

        return $this->valueResolver->isValue($arrayDimFetch->dim, $value);
    }

    private function matchArrayDimFetchAndConstFetch(Identical|NotIdentical $identical): ?ArrayDimFetchAndConstFetch
    {
        if ($identical->left instanceof ArrayDimFetch && $identical->right instanceof ConstFetch) {
            return new ArrayDimFetchAndConstFetch($identical->left, $identical->right);
        }

        if (! $identical->right instanceof ArrayDimFetch) {
            return null;
        }

        if (! $identical->left instanceof ConstFetch) {
            return null;
        }

        return new ArrayDimFetchAndConstFetch($identical->right, $identical->left);
    }

    private function createIsTConstTypeMethodCall(ArrayDimFetch $arrayDimFetch, ConstFetch $constFetch): MethodCall
    {
        return new MethodCall($arrayDimFetch->var, 'is', [new Arg($constFetch)]);
    }

    private function shouldSkipNodeRemovalForPartOfIf(FuncCall $funcCall): bool
    {
        $parentNode = $funcCall->getAttribute(AttributeKey::PARENT_NODE);

        // cannot remove x from if(x)
        if ($parentNode instanceof If_ && $parentNode->cond === $funcCall) {
            return true;
        }

        if ($parentNode instanceof BooleanNot) {
            $parentParentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);
            if ($parentParentNode instanceof If_) {
                $parentParentNode->cond = $parentNode;
                return true;
            }
        }

        return false;
    }

    private function matchParentNodeInCaseOfIdenticalTrue(FuncCall $funcCall): Identical | FuncCall
    {
        $parentNode = $funcCall->getAttribute(AttributeKey::PARENT_NODE);
        if ($parentNode instanceof Identical) {
            $isRightValueTrue = $this->valueResolver->isValue($parentNode->right, true);
            if ($parentNode->left === $funcCall && $isRightValueTrue) {
                return $parentNode;
            }

            $isLeftValueTrue = $this->valueResolver->isValue($parentNode->left, true);
            if ($parentNode->right === $funcCall && $isLeftValueTrue) {
                return $parentNode;
            }
        }

        return $funcCall;
    }
}
