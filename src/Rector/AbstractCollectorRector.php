<?php

declare(strict_types=1);

namespace Rector\Core\Rector;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Collectors\CollectedData;
use PHPStan\Node\CollectedDataNode;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\Application\ChangedNodeScopeRefresher;
use Rector\Core\Contract\Rector\CollectorRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\NodeDecorator\CreatedByRuleDecorator;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\Skipper\Skipper\Skipper;
use Webmozart\Assert\Assert;

abstract class AbstractCollectorRector extends NodeVisitorAbstract implements CollectorRectorInterface
{
    /**
     * @todo extract
     * @var string
     */
    private const EMPTY_NODE_ARRAY_MESSAGE = <<<CODE_SAMPLE
Array of nodes cannot be empty. Ensure "%s->refactor()" returns non-empty array for Nodes.

A) Direct return null for no change:

    return null;

B) Remove the Node:

    return NodeTraverser::REMOVE_NODE;
CODE_SAMPLE;

    private File $file;

    private ChangedNodeScopeRefresher $changedNodeScopeRefresher;

    private SimpleCallableNodeTraverser $simpleCallableNodeTraverser;

    private Skipper $skipper;

    private CurrentFileProvider $currentFileProvider;

    /**
     * @var array<int, Node[]>
     */
    private array $nodesToReturn = [];

    private CreatedByRuleDecorator $createdByRuleDecorator;

    private ?int $toBeRemovedNodeId = null;

    /**
     * @var CollectedData[]
     */
    private array $collectedDatas = [];

    /**
     * @param CollectedData[] $collectedDatas
     */
    public function setCollectedDatas(array $collectedDatas): void
    {
        Assert::allIsInstanceOf($collectedDatas, CollectedData::class);
        $this->collectedDatas = $collectedDatas;
    }

    public function autowire(
        SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        Skipper $skipper,
        CurrentFileProvider $currentFileProvider,
        CreatedByRuleDecorator $createdByRuleDecorator,
        ChangedNodeScopeRefresher $changedNodeScopeRefresher,
    ): void {
        $this->simpleCallableNodeTraverser = $simpleCallableNodeTraverser;
        $this->skipper = $skipper;
        $this->currentFileProvider = $currentFileProvider;
        $this->createdByRuleDecorator = $createdByRuleDecorator;
        $this->changedNodeScopeRefresher = $changedNodeScopeRefresher;
    }

    /**
     * @return Node[]|null
     */
    public function beforeTraverse(array $nodes): ?array
    {
        // workaround for file around refactor()
        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            throw new ShouldNotHappenException(
                'File object is missing. Make sure you call $this->currentFileProvider->setFile(...) before traversing.'
            );
        }

        $this->file = $file;

        return parent::beforeTraverse($nodes);
    }

    final public function enterNode(Node $node): int|Node|null
    {
        if (! $this->isMatchingNodeType($node)) {
            return null;
        }

        $filePath = $this->file->getFilePath();
        if ($this->skipper->shouldSkipCurrentNode($this, $filePath, static::class, $node)) {
            return null;
        }

        $this->changedNodeScopeRefresher->reIndexNodeAttributes($node);

        // ensure origNode pulled before refactor to avoid changed during refactor, ref https://3v4l.org/YMEGN
        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE) ?? $node;

        $collectedDataNode = new CollectedDataNode($this->collectedDatas, false);
        $refactoredNode = $this->refactor($node, $collectedDataNode);

        // @see NodeTraverser::* codes, e.g. removal of node of stopping the traversing
        if ($refactoredNode === NodeTraverser::REMOVE_NODE) {
            $this->toBeRemovedNodeId = spl_object_id($originalNode);

            // notify this rule changing code
            $rectorWithLineChange = new RectorWithLineChange(static::class, $originalNode->getLine());
            $this->file->addRectorClassWithLine($rectorWithLineChange);

            return $originalNode;
        }

        if (is_int($refactoredNode)) {
            $this->createdByRuleDecorator->decorate($node, $originalNode, static::class);

            if (! in_array(
                $refactoredNode,
                [NodeTraverser::DONT_TRAVERSE_CHILDREN, NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN],
                true
            )) {
                // notify this rule changing code
                $rectorWithLineChange = new RectorWithLineChange(static::class, $originalNode->getLine());
                $this->file->addRectorClassWithLine($rectorWithLineChange);

                return $refactoredNode;
            }

            $this->decorateCurrentAndChildren($node);
            return null;
        }

        // nothing to change → continue
        if ($refactoredNode === null) {
            return null;
        }

        if ($refactoredNode === []) {
            $errorMessage = sprintf(self::EMPTY_NODE_ARRAY_MESSAGE, static::class);
            throw new ShouldNotHappenException($errorMessage);
        }

        return $this->postRefactorProcess($originalNode, $node, $refactoredNode, $filePath);
    }

    /**
     * Replacing nodes in leaveNode() method avoids infinite recursion
     * see"infinite recursion" in https://github.com/nikic/PHP-Parser/blob/master/doc/component/Walking_the_AST.markdown
     */
    public function leaveNode(Node $node): array|int|Node|null
    {
        if ($node->hasAttribute(AttributeKey::ORIGINAL_NODE)) {
            return null;
        }

        $objectId = spl_object_id($node);
        if ($this->toBeRemovedNodeId === $objectId) {
            $this->toBeRemovedNodeId = null;

            return NodeTraverser::REMOVE_NODE;
        }

        return $this->nodesToReturn[$objectId] ?? $node;
    }

    private function decorateCurrentAndChildren(Node $node): void
    {
        // filter only types that
        //    1. registered in getNodesTypes() method
        //    2. different with current node type, as already decorated above
        $otherTypes = array_filter(
            $this->getNodeTypes(),
            static fn (string $nodeType): bool => $nodeType !== $node::class
        );

        if ($otherTypes === []) {
            return;
        }

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($node, static function (Node $subNode) use (
            $otherTypes
        ) {
            if (in_array($subNode::class, $otherTypes, true)) {
                $subNode->setAttribute(AttributeKey::SKIPPED_BY_RECTOR_RULE, static::class);
            }

            return null;
        });
    }

    /**
     * @param Node|Node[] $refactoredNode
     */
    private function postRefactorProcess(
        Node $originalNode,
        Node $node,
        Node|array|int $refactoredNode,
        string $filePath
    ): Node {
        /** @var non-empty-array<Node>|Node $refactoredNode */
        $this->createdByRuleDecorator->decorate($refactoredNode, $originalNode, static::class);

        $rectorWithLineChange = new RectorWithLineChange(static::class, $originalNode->getLine());
        $this->file->addRectorClassWithLine($rectorWithLineChange);

        /** @var MutatingScope|null $currentScope */
        $currentScope = $node->getAttribute(AttributeKey::SCOPE);

        if (is_array($refactoredNode)) {
            $this->refreshScopeNodes($refactoredNode, $filePath, $currentScope);

            // search "infinite recursion" in https://github.com/nikic/PHP-Parser/blob/master/doc/component/Walking_the_AST.markdown
            $originalNodeId = spl_object_id($originalNode);

            // will be replaced in leaveNode() the original node must be passed
            $this->nodesToReturn[$originalNodeId] = $refactoredNode;

            return $originalNode;
        }

        $this->refreshScopeNodes($refactoredNode, $filePath, $currentScope);
        return $refactoredNode;
    }

    /**
     * @param Node[]|Node $node
     */
    private function refreshScopeNodes(array | Node $node, string $filePath, ?MutatingScope $mutatingScope): void
    {
        $nodes = $node instanceof Node ? [$node] : $node;

        foreach ($nodes as $node) {
            $this->changedNodeScopeRefresher->refresh($node, $mutatingScope, $filePath);
        }
    }

    private function isMatchingNodeType(Node $node): bool
    {
        $nodeClass = $node::class;
        foreach ($this->getNodeTypes() as $nodeType) {
            if (is_a($nodeClass, $nodeType, true)) {
                return true;
            }
        }

        return false;
    }
}
