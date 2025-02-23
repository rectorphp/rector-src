<?php

declare(strict_types=1);

namespace Rector\PhpParser\Node;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeFinder;
use PhpParser\NodeVisitor;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\PhpParser\Node\BetterNodeFinder\BetterNodeFinderTest
 */
final readonly class BetterNodeFinder
{
    public function __construct(
        private NodeFinder $nodeFinder,
        private NodeNameResolver $nodeNameResolver,
        private ClassAnalyzer $classAnalyzer,
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser
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
            $foundInstances = [...$foundInstances, ...$currentFoundInstances];
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
     * @param Node|Node[] $nodes
     *
     * @return T|null
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

        return (bool) $this->nodeFinder->findFirst($nodes, static function (Node $node) use ($types): bool {
            foreach ($types as $type) {
                if ($node instanceof $type) {
                    return true;
                }
            }

            return false;
        });
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
     * @return Class_|null
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
                if ($subNode instanceof Class_ || $subNode instanceof FunctionLike) {
                    return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                foreach ($types as $type) {
                    if ($subNode instanceof $type) {
                        $isFoundNode = true;
                        return NodeVisitor::STOP_TRAVERSAL;
                    }
                }

                return null;
            }
        );

        return $isFoundNode;
    }

    /**
     * @return Return_[]
     */
    public function findReturnsScoped(ClassMethod | Function_ | Closure $functionLike): array
    {
        $returns = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $functionLike->stmts,
            function (Node $subNode) use (&$returns): ?int {
                if ($subNode instanceof Class_ || $subNode instanceof FunctionLike) {
                    return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if ($subNode instanceof Yield_ || $subNode instanceof YieldFrom) {
                    $returns = [];
                    return NodeVisitor::STOP_TRAVERSAL;
                }

                if ($subNode instanceof Return_) {
                    $returns[] = $subNode;
                }

                return null;
            }
        );

        return $returns;
    }

    /**
     * @api to be used
     *
     * @template T of Node
     * @param Node[] $nodes
     * @param class-string<T>|array<class-string<T>> $types
     * @return T[]
     */
    public function findInstancesOfScoped(array $nodes, string|array $types): array
    {
        // here verify only pass single nodes as FunctionLike
        if (count($nodes) === 1
            && ($nodes[0] instanceof FunctionLike)) {
            $nodes = (array) $nodes[0]->getStmts();
        }

        if (is_string($types)) {
            $types = [$types];
        }

        /** @var T[] $foundNodes */
        $foundNodes = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $nodes,
            static function (Node $subNode) use ($types, &$foundNodes): ?int {
                if ($subNode instanceof Class_ || $subNode instanceof FunctionLike) {
                    return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
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
     * @template T of Node
     * @param array<class-string<T>>|class-string<T> $types
     * @return array<T>
     */
    public function findInstancesOfInFunctionLikeScoped(
        ClassMethod | Function_ | Closure $functionLike,
        string|array $types
    ): array {
        return $this->findInstancesOfScoped([$functionLike], $types);
    }

    /**
     * @param callable(Node $node): bool $filter
     */
    public function findFirstInFunctionLikeScoped(
        ClassMethod | Function_ | Closure $functionLike,
        callable $filter
    ): ?Node {
        $scopedNode = null;

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $functionLike->stmts,
            function (Node $subNode) use (&$scopedNode, $filter): ?int {
                if (! $filter($subNode)) {
                    if ($subNode instanceof Class_ || $subNode instanceof FunctionLike) {
                        return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                    }

                    return null;
                }

                $scopedNode = $subNode;
                return NodeVisitor::STOP_TRAVERSAL;
            }
        );

        return $scopedNode;
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
