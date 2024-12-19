<?php

declare(strict_types=1);

namespace Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\PropertyItem;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\Application\ChangedNodeScopeRefresher;
use Rector\Application\NodeAttributeReIndexer;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Contract\Rector\HTMLAverseRectorInterface;
use Rector\Contract\Rector\RectorInterface;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeDecorator\CreatedByRuleDecorator;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Comparing\NodeComparator;
use Rector\PhpParser\Node\NodeFactory;
use Rector\Skipper\Skipper\Skipper;
use Rector\ValueObject\Application\File;

abstract class AbstractRector extends NodeVisitorAbstract implements RectorInterface
{
    /**
     * @var string
     */
    private const EMPTY_NODE_ARRAY_MESSAGE = <<<CODE_SAMPLE
Array of nodes cannot be empty. Ensure "%s->refactor()" returns non-empty array for Nodes.

A) Direct return null for no change:

    return null;

B) Remove the Node:

    return \PhpParser\NodeVisitor::REMOVE_NODE;
CODE_SAMPLE;

    protected NodeNameResolver $nodeNameResolver;

    protected NodeTypeResolver $nodeTypeResolver;

    protected NodeFactory $nodeFactory;

    protected NodeComparator $nodeComparator;

    protected File $file;

    protected Skipper $skipper;

    private ChangedNodeScopeRefresher $changedNodeScopeRefresher;

    private SimpleCallableNodeTraverser $simpleCallableNodeTraverser;

    private CurrentFileProvider $currentFileProvider;

    /**
     * @var array<int, Node[]>
     */
    private array $nodesToReturn = [];

    private CreatedByRuleDecorator $createdByRuleDecorator;

    private ?int $toBeRemovedNodeId = null;

    public function autowire(
        NodeNameResolver $nodeNameResolver,
        NodeTypeResolver $nodeTypeResolver,
        SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        NodeFactory $nodeFactory,
        Skipper $skipper,
        NodeComparator $nodeComparator,
        CurrentFileProvider $currentFileProvider,
        CreatedByRuleDecorator $createdByRuleDecorator,
        ChangedNodeScopeRefresher $changedNodeScopeRefresher,
    ): void {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->nodeTypeResolver = $nodeTypeResolver;
        $this->simpleCallableNodeTraverser = $simpleCallableNodeTraverser;
        $this->nodeFactory = $nodeFactory;
        $this->skipper = $skipper;
        $this->nodeComparator = $nodeComparator;
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

        if (is_a($this, HTMLAverseRectorInterface::class, true) && $this->file->containsHTML()) {
            return null;
        }

        $filePath = $this->file->getFilePath();
        if ($this->skipper->shouldSkipCurrentNode($this, $filePath, static::class, $node)) {
            return null;
        }

        // ensure origNode pulled before refactor to avoid changed during refactor, ref https://3v4l.org/YMEGN
        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE) ?? $node;

        NodeAttributeReIndexer::reIndexNodeAttributes($node);

        $refactoredNode = $this->refactor($node);

        // @see NodeTraverser::* codes, e.g. removal of node of stopping the traversing
        if ($refactoredNode === NodeVisitor::REMOVE_NODE) {
            $this->toBeRemovedNodeId = spl_object_id($originalNode);

            // notify this rule changing code
            $rectorWithLineChange = new RectorWithLineChange(static::class, $originalNode->getStartLine());
            $this->file->addRectorClassWithLine($rectorWithLineChange);

            return $originalNode;
        }

        if (is_int($refactoredNode)) {
            $this->createdByRuleDecorator->decorate($node, $originalNode, static::class);

            if (! in_array(
                $refactoredNode,
                [NodeVisitor::DONT_TRAVERSE_CHILDREN, NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN],
                true
            )) {
                // notify this rule changing code
                $rectorWithLineChange = new RectorWithLineChange(static::class, $originalNode->getStartLine());
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

            return NodeVisitor::REMOVE_NODE;
        }

        return $this->nodesToReturn[$objectId] ?? $node;
    }

    protected function isName(Node $node, string $name): bool
    {
        return $this->nodeNameResolver->isName($node, $name);
    }

    /**
     * @param string[] $names
     */
    protected function isNames(Node $node, array $names): bool
    {
        return $this->nodeNameResolver->isNames($node, $names);
    }

    /**
     * Some nodes have always-known string name. This makes PHPStan smarter.
     * @see https://phpstan.org/writing-php-code/phpdoc-types#conditional-return-types
     *
     * @return ($node is Node\Param ? string :
     *  ($node is ClassMethod ? string :
     *  ($node is Property ? string :
     *  ($node is PropertyItem ? string :
     *  ($node is Trait_ ? string :
     *  ($node is Interface_ ? string :
     *  ($node is Const_ ? string :
     *  ($node is Node\Const_ ? string :
     *  ($node is Name ? string :
     *      string|null )))))))))
     */
    protected function getName(Node $node): ?string
    {
        return $this->nodeNameResolver->getName($node);
    }

    protected function isObjectType(Node $node, ObjectType $objectType): bool
    {
        return $this->nodeTypeResolver->isObjectType($node, $objectType);
    }

    /**
     * Use this method for getting expr|node type
     */
    protected function getType(Node $node): Type
    {
        return $this->nodeTypeResolver->getType($node);
    }

    /**
     * @param Node|Node[] $nodes
     * @param callable(Node): (int|Node|null|Node[]) $callable
     */
    protected function traverseNodesWithCallable(Node | array $nodes, callable $callable): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, $callable);
    }

    protected function mirrorComments(Node $newNode, Node $oldNode): void
    {
        if ($this->nodeComparator->areSameNode($newNode, $oldNode)) {
            return;
        }

        if ($oldNode instanceof InlineHTML) {
            return;
        }

        $oldPhpDocInfo = $oldNode->getAttribute(AttributeKey::PHP_DOC_INFO);
        $newPhpDocInfo = $newNode->getAttribute(AttributeKey::PHP_DOC_INFO);

        if ($newPhpDocInfo instanceof PhpDocInfo) {
            if (! $oldPhpDocInfo instanceof PhpDocInfo) {
                return;
            }

            if ((string) $oldPhpDocInfo->getPhpDocNode() !== (string) $newPhpDocInfo->getPhpDocNode()) {
                return;
            }
        }

        $newNode->setAttribute(AttributeKey::PHP_DOC_INFO, $oldPhpDocInfo);
        if (! $newNode instanceof Nop) {
            $newNode->setAttribute(AttributeKey::COMMENTS, $oldNode->getAttribute(AttributeKey::COMMENTS));
        }
    }

    private function decorateCurrentAndChildren(Node $node): void
    {
        // filter only types that
        //    1. registered in getNodesTypes() method
        //    2. different with current node type, as already decorated above
        //
        $otherTypes = array_filter(
            $this->getNodeTypes(),
            static fn (string $nodeType): bool => $nodeType !== $node::class
        );

        if ($otherTypes === []) {
            return;
        }

        $this->traverseNodesWithCallable($node, static function (Node $subNode) use ($otherTypes): null {
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

        $rectorWithLineChange = new RectorWithLineChange(static::class, $originalNode->getStartLine());
        $this->file->addRectorClassWithLine($rectorWithLineChange);

        /** @var MutatingScope|null $currentScope */
        $currentScope = $node->getAttribute(AttributeKey::SCOPE);

        if (is_array($refactoredNode)) {
            $firstNode = current($refactoredNode);
            $this->mirrorComments($firstNode, $originalNode);

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
            $this->changedNodeScopeRefresher->refresh($node, $filePath, $mutatingScope);
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
