<?php

declare(strict_types=1);

namespace Rector\NodeNestingScope;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\If_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNestingScope\ValueObject\ControlStructure;

final class ContextAnalyzer
{
    /**
     * Nodes that break the scope they way up, e.g. class method
     * @var array<class-string<FunctionLike>>
     */
    private const BREAK_NODES = [FunctionLike::class, ClassMethod::class];

    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function isInLoop(Node $node): bool
    {
        $firstParent = $this->betterNodeFinder->findParentByTypes(
            $node,
            [...ControlStructure::LOOP_NODES, ...self::BREAK_NODES]
        );
        if (! $firstParent instanceof Node) {
            return false;
        }

        foreach (ControlStructure::LOOP_NODES as $type) {
            if (is_a($firstParent, $type, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @api
     */
    public function isInIf(Node $node): bool
    {
        $previousNode = $this->betterNodeFinder->findParentByTypes($node, [If_::class, ...self::BREAK_NODES]);
        if (! $previousNode instanceof Node) {
            return false;
        }

        return $previousNode instanceof If_;
    }
}
