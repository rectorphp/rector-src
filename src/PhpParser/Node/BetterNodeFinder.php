<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Node;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Core\Tests\PhpParser\Node\BetterNodeFinder\BetterNodeFinderTest
 */
final class BetterNodeFinder
{
    public function __construct(
        private readonly NodeFinder $nodeFinder,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    /**
     * @template T of Node
     * @param array<class-string<T>> $types
     * @param Node|Node[]|Stmt[] $nodes
     * @return T[]
     */
    public function findInstancesOf(Node | array $nodes, array $types): array
    {
        $foundInstances = [];
        foreach ($types as $type) {
            $currentFoundInstances = $this->findInstanceOf($nodes, $type);
            $foundInstances = array_merge($foundInstances, $currentFoundInstances);
        }

        return $foundInstances;
    }

    /**
     * @template T of Node
     * @param class-string<T> $type
     * @param Node|Node[]|Stmt[] $nodes
     * @return T[]
     */
    public function findInstanceOf(Node | array $nodes, string $type): array
    {
        return $this->nodeFinder->findInstanceOf($nodes, $type);
    }

    /**
     * @template T of Node
     * @param class-string<T> $type
     * @return T|null
     *
     * @param Node|Node[] $nodes
     */
    public function findFirstInstanceOf(Node | array $nodes, string $type): ?Node
    {
        Assert::isAOf($type, Node::class);
        return $this->nodeFinder->findFirstInstanceOf($nodes, $type);
    }

    /**
     * @param class-string<Node> $type
     * @param Node[] $nodes
     */
    public function hasInstanceOfName(array $nodes, string $type, string $name): bool
    {
        Assert::isAOf($type, Node::class);
        return (bool) $this->findInstanceOfName($nodes, $type, $name);
    }

    /**
     * @param Node[] $nodes
     */
    public function hasVariableOfName(array $nodes, string $name): bool
    {
        return $this->findVariableOfName($nodes, $name) instanceof Node;
    }

    /**
     * @api
     * @param Node|Node[] $nodes
     * @return Variable|null
     */
    public function findVariableOfName(Node | array $nodes, string $name): ?Node
    {
        return $this->findInstanceOfName($nodes, Variable::class, $name);
    }

    /**
     * @param Node|Node[] $nodes
     * @param array<class-string<Node>> $types
     */
    public function hasInstancesOf(Node | array $nodes, array $types): bool
    {
        Assert::allIsAOf($types, Node::class);

        foreach ($types as $type) {
            $foundNode = $this->nodeFinder->findFirstInstanceOf($nodes, $type);
            if (! $foundNode instanceof Node) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @param Node|Node[] $nodes
     * @param callable(Node $node): bool $filter
     * @return Node[]
     */
    public function find(Node | array $nodes, callable $filter): array
    {
        return $this->nodeFinder->find($nodes, $filter);
    }

    /**
     * @api symfony
     * @param Node[] $nodes
     * @return ClassLike|null
     */
    public function findFirstNonAnonymousClass(array $nodes): ?Node
    {
        // skip anonymous classes
        return $this->findFirst($nodes, fn (Node $node): bool =>
            $node instanceof Class_ && ! $this->classAnalyzer->isAnonymousClass($node));
    }

    /**
     * @param Node|Node[] $nodes
     * @param callable(Node $filter): bool $filter
     */
    public function findFirst(Node | array $nodes, callable $filter): ?Node
    {
        return $this->nodeFinder->findFirst($nodes, $filter);
    }

    /**
     * @template T of Node
     * @param array<class-string<T>>|class-string<T> $types
     */
    public function hasInstancesOfInFunctionLikeScoped(
        ClassMethod | Function_ | Closure $functionLike,
        string|array $types
    ): bool {
        if (is_string($types)) {
            $types = [$types];
        }

        $isFoundNode = false;
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $functionLike->stmts,
            static function (Node $subNode) use ($types, &$isFoundNode): ?int {
                if ($subNode instanceof Class_ || $subNode instanceof Function_ || $subNode instanceof Closure) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                foreach ($types as $type) {
                    if ($subNode instanceof $type) {
                        $isFoundNode = true;
                        return NodeTraverser::STOP_TRAVERSAL;
                    }
                }

                return null;
            }
        );

        return $isFoundNode;
    }

    /**
     * @template T of Node
     * @param array<class-string<T>>|class-string<T> $types
     * @return T[]
     */
    public function findInstancesOfInFunctionLikeScoped(
        ClassMethod | Function_ | Closure $functionLike,
        string|array $types
    ): array {
        if (is_string($types)) {
            $types = [$types];
        }

        /** @var T[] $foundNodes */
        $foundNodes = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $functionLike->stmts,
            static function (Node $subNode) use ($types, &$foundNodes): ?int {
                if ($subNode instanceof Class_ || $subNode instanceof Function_ || $subNode instanceof Closure) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                foreach ($types as $type) {
                    if ($subNode instanceof $type) {
                        $foundNodes[] = $subNode;
                        return null;
                    }
                }

                return null;
            }
        );

        return $foundNodes;
    }

    /**
     * @param callable(Node $node): bool $filter
     */
    public function findFirstInFunctionLikeScoped(
        ClassMethod | Function_ | Closure $functionLike,
        callable $filter
    ): ?Node {
        if ($functionLike->stmts === null) {
            return null;
        }

        $foundNode = $this->findFirst($functionLike->stmts, $filter);
        if (! $foundNode instanceof Node) {
            return null;
        }

        if (! $this->hasInstancesOf($functionLike->stmts, [Class_::class, Function_::class, Closure::class])) {
            return $foundNode;
        }

        $scopedNode = null;
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $functionLike->stmts,
            static function (Node $subNode) use (&$scopedNode, $foundNode): ?int {
                if ($subNode instanceof Class_ || $subNode instanceof Function_ || $subNode instanceof Closure) {
                    if ($foundNode instanceof $subNode && $subNode === $foundNode) {
                        $scopedNode = $subNode;
                        return NodeTraverser::STOP_TRAVERSAL;
                    }

                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if ($foundNode instanceof $subNode && $subNode === $foundNode) {
                    $scopedNode = $subNode;
                    return NodeTraverser::STOP_TRAVERSAL;
                }

                return null;
            }
        );

        return $scopedNode;
    }

    /**
     * @api
     *
     * Resolve next node from any Node, eg: Expr, Identifier, Name, etc
     */
    public function resolveNextNode(Node $node): ?Node
    {
        $currentStmt = $this->resolveCurrentStatement($node);
        if (! $currentStmt instanceof Stmt) {
            return null;
        }

        $endTokenPos = $node->getEndTokenPos();
        $nextNode = $endTokenPos < 0 || $currentStmt->getEndTokenPos() === $endTokenPos
            ? null
            : $this->findFirst(
                $currentStmt,
                static fn (Node $subNode): bool => $subNode->getStartTokenPos() > $endTokenPos
            );

        if (! $nextNode instanceof Node) {
            $parentNode = $currentStmt->getAttribute(AttributeKey::PARENT_NODE);
            if (! $this->isAllowedParentNode($parentNode)) {
                return null;
            }

            $currentStmtKey = $currentStmt->getAttribute(AttributeKey::STMT_KEY);
            /** @var StmtsAwareInterface|ClassLike|Declare_ $parentNode */
            return $parentNode->stmts[$currentStmtKey + 1] ?? null;
        }

        return $nextNode;
    }

    private function resolveCurrentStatement(Node $node): ?Stmt
    {
        if ($node instanceof Stmt) {
            return $node;
        }

        $currentStmt = $node;
        while (($currentStmt = $currentStmt->getAttribute(AttributeKey::PARENT_NODE)) instanceof Node) {
            if ($currentStmt instanceof Stmt) {
                return $currentStmt;
            }

            /** @var Node|null $currentStmt */
            if (! $currentStmt instanceof Node) {
                return null;
            }
        }

        return null;
    }

    private function isAllowedParentNode(?Node $node): bool
    {
        return $node instanceof StmtsAwareInterface || $node instanceof ClassLike || $node instanceof Declare_;
    }

    /**
     * @template T of Node
     * @param Node|Node[] $nodes
     * @param class-string<T> $type
     */
    private function findInstanceOfName(Node | array $nodes, string $type, string $name): ?Node
    {
        Assert::isAOf($type, Node::class);

        return $this->nodeFinder->findFirst($nodes, fn (Node $node): bool =>
            $node instanceof $type && $this->nodeNameResolver->isName($node, $name));
    }
}
