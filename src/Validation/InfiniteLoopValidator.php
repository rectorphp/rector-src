<?php

declare(strict_types=1);

namespace Rector\Core\Validation;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\Exception\NodeTraverser\InfiniteLoopTraversingException;
use Rector\Core\NodeDecorator\CreatedByRuleDecorator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\NodeVisitor\CreatedByRuleNodeVisitor;
use Rector\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector;
use Rector\DowngradePhp80\Rector\NullsafeMethodCall\DowngradeNullsafeToTernaryOperatorRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;

final class InfiniteLoopValidator
{
    /**
     * @var array<class-string<RectorInterface>>
     */
    private const ALLOWED_INFINITE_RECTOR_CLASSES = [
        DowngradeNullsafeToTernaryOperatorRector::class,
        ArrowFunctionToAnonymousFunctionRector::class,
        ClosureToArrowFunctionRector::class,
    ];

    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly CreatedByRuleDecorator $createdByRuleDecorator
    ) {
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function process(Node $node, Node $originalNode, string $rectorClass): void
    {
        if (in_array($rectorClass, self::ALLOWED_INFINITE_RECTOR_CLASSES, true)) {
            return;
        }

        $createdByRule = $originalNode->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [];

        // special case
        if (in_array($rectorClass, $createdByRule, true)) {
            // does it contain the same node type as input?
            $originalNodeClass = $originalNode::class;

            $hasNestedOriginalNodeType = $this->betterNodeFinder->findInstanceOf($node, $originalNodeClass);
            if ($hasNestedOriginalNodeType !== []) {
                throw new InfiniteLoopTraversingException($rectorClass);
            }
        }

        $this->decorateNode($originalNode, $rectorClass);
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    private function decorateNode(Node $node, string $rectorClass): void
    {
        $nodeTraverser = new NodeTraverser();

        $createdByRuleNodeVisitor = new CreatedByRuleNodeVisitor($this->createdByRuleDecorator, $rectorClass);
        $nodeTraverser->addVisitor($createdByRuleNodeVisitor);

        $nodeTraverser->traverse([$node]);
    }
}
