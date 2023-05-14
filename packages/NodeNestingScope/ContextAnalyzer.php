<?php

declare(strict_types=1);

namespace Rector\NodeNestingScope;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Switch_;
use PHPStan\Type\ObjectType;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNestingScope\ValueObject\ControlStructure;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class ContextAnalyzer
{
    /**
     * Nodes that break the scope they way up, e.g. class method
     * @var array<class-string<FunctionLike>>
     */
    private const BREAK_NODES = [FunctionLike::class, ClassMethod::class];

    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeTypeResolver $nodeTypeResolver,
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
    public function isInSwitch(Node $node): bool
    {
        return (bool) $this->betterNodeFinder->findParentType($node, Switch_::class);
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

    public function hasAssignWithIndirectReturn(Node\Stmt $stmt, If_ $if): bool
    {
        foreach (ControlStructure::LOOP_NODES as $loopNode) {
            $loopObjectType = new ObjectType($loopNode);
            $parentType = $this->nodeTypeResolver->getType($stmt);

            $superType = $parentType->isSuperTypeOf($loopObjectType);
            if (! $superType->yes()) {
                continue;
            }

            $nextNode = $stmt->getAttribute(AttributeKey::NEXT_NODE);
            if ($nextNode instanceof Node) {
                if ($nextNode instanceof Return_ && ! $nextNode->expr instanceof Expr) {
                    continue;
                }

                $hasAssign = (bool) $this->betterNodeFinder->findFirstInstanceOf($if->stmts, Assign::class);
                if (! $hasAssign) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }
}
