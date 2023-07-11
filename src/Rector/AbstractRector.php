<?php

declare(strict_types=1);

namespace Rector\Core\Rector;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Internal\BytesHelper;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\Application\ChangedNodeScopeRefresher;
use Rector\Core\Configuration\CurrentNodeProvider;
use Rector\Core\Console\Output\RectorOutputStyle;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\Logging\CurrentRectorProvider;
use Rector\Core\NodeDecorator\CreatedByRuleDecorator;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\Core\ProcessAnalyzer\RectifiedAnalyzer;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\Skipper\Skipper\Skipper;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRector extends NodeVisitorAbstract implements PhpRectorInterface
{
    /**
     * @var string
     */
    private const EMPTY_NODE_ARRAY_MESSAGE = <<<CODE_SAMPLE
Array of nodes cannot be empty. Ensure "%s->refactor()" returns non-empty array for Nodes.

A) Direct return null for no change:

    return null;

B) Remove the Node:

    return NodeTraverser::REMOVE_NODE;
CODE_SAMPLE;

    protected NodeNameResolver $nodeNameResolver;

    protected NodeTypeResolver $nodeTypeResolver;

    protected StaticTypeMapper $staticTypeMapper;

    protected PhpDocInfoFactory $phpDocInfoFactory;

    protected NodeFactory $nodeFactory;

    protected ValueResolver $valueResolver;

    protected BetterNodeFinder $betterNodeFinder;

    protected NodeComparator $nodeComparator;

    protected File $file;

    private ChangedNodeScopeRefresher $changedNodeScopeRefresher;

    private SimpleCallableNodeTraverser $simpleCallableNodeTraverser;

    private CurrentRectorProvider $currentRectorProvider;

    private CurrentNodeProvider $currentNodeProvider;

    private Skipper $skipper;

    private CurrentFileProvider $currentFileProvider;

    /**
     * @var array<string, Node[]|Node>
     */
    private array $nodesToReturn = [];

    private RectifiedAnalyzer $rectifiedAnalyzer;

    private CreatedByRuleDecorator $createdByRuleDecorator;

    private RectorOutputStyle $rectorOutputStyle;

    private FilePathHelper $filePathHelper;

    private ?string $toBeRemovedNodeHash = null;

    #[Required]
    public function autowire(
        NodeNameResolver $nodeNameResolver,
        NodeTypeResolver $nodeTypeResolver,
        SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        NodeFactory $nodeFactory,
        PhpDocInfoFactory $phpDocInfoFactory,
        StaticTypeMapper $staticTypeMapper,
        CurrentRectorProvider $currentRectorProvider,
        CurrentNodeProvider $currentNodeProvider,
        Skipper $skipper,
        ValueResolver $valueResolver,
        BetterNodeFinder $betterNodeFinder,
        NodeComparator $nodeComparator,
        CurrentFileProvider $currentFileProvider,
        RectifiedAnalyzer $rectifiedAnalyzer,
        CreatedByRuleDecorator $createdByRuleDecorator,
        ChangedNodeScopeRefresher $changedNodeScopeRefresher,
        RectorOutputStyle $rectorOutputStyle,
        FilePathHelper $filePathHelper,
    ): void {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->nodeTypeResolver = $nodeTypeResolver;
        $this->simpleCallableNodeTraverser = $simpleCallableNodeTraverser;
        $this->nodeFactory = $nodeFactory;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->currentRectorProvider = $currentRectorProvider;
        $this->currentNodeProvider = $currentNodeProvider;
        $this->skipper = $skipper;
        $this->valueResolver = $valueResolver;
        $this->betterNodeFinder = $betterNodeFinder;
        $this->nodeComparator = $nodeComparator;
        $this->currentFileProvider = $currentFileProvider;
        $this->rectifiedAnalyzer = $rectifiedAnalyzer;
        $this->createdByRuleDecorator = $createdByRuleDecorator;
        $this->changedNodeScopeRefresher = $changedNodeScopeRefresher;
        $this->rectorOutputStyle = $rectorOutputStyle;
        $this->filePathHelper = $filePathHelper;
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

    final public function enterNode(Node $node)
    {
        $nodeClass = $node::class;
        if (! $this->isMatchingNodeType($nodeClass)) {
            return null;
        }

        if ($this->shouldSkipCurrentNode($node)) {
            return null;
        }

        $isDebug = $this->rectorOutputStyle->isDebug();

        $this->currentRectorProvider->changeCurrentRector($this);
        // for PHP doc info factory and change notifier
        $this->currentNodeProvider->setNode($node);

        if ($isDebug) {
            $this->printCurrentFileAndRule();
        }

        $this->changedNodeScopeRefresher->reIndexNodeAttributes($node);

        if ($isDebug) {
            $startTime = microtime(true);
            $previousMemory = memory_get_peak_usage(true);
        }

        // ensure origNode pulled before refactor to avoid changed during refactor, ref https://3v4l.org/YMEGN
        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE) ?? $node;
        $refactoredNode = $this->refactor($node);

        if ($isDebug) {
            $this->printConsumptions($startTime, $previousMemory);
        }

        // @see NodeTraverser::* codes, e.g. removal of node of stopping the traversing
        if ($refactoredNode === NodeTraverser::REMOVE_NODE) {
            $this->toBeRemovedNodeHash = spl_object_hash($originalNode);

            // notify this rule changing code
            $rectorWithLineChange = new RectorWithLineChange(static::class, $originalNode->getLine());
            $this->file->addRectorClassWithLine($rectorWithLineChange);

            return $originalNode;
        }

        if (is_int($refactoredNode)) {
            $this->createdByRuleDecorator->decorate($node, $originalNode, static::class);

            // notify this rule changing code
            $rectorWithLineChange = new RectorWithLineChange(static::class, $originalNode->getLine());
            $this->file->addRectorClassWithLine($rectorWithLineChange);

            return $refactoredNode;
        }

        // nothing to change → continue
        if ($refactoredNode === null) {
            return null;
        }

        if ($refactoredNode === []) {
            $errorMessage = sprintf(self::EMPTY_NODE_ARRAY_MESSAGE, static::class);
            throw new ShouldNotHappenException($errorMessage);
        }

        return $this->postRefactorProcess($originalNode, $refactoredNode);
    }

    /**
     * Replacing nodes in leaveNode() method avoids infinite recursion
     * see"infinite recursion" in https://github.com/nikic/PHP-Parser/blob/master/doc/component/Walking_the_AST.markdown
     */
    public function leaveNode(Node $node)
    {
        if ($this->toBeRemovedNodeHash !== null && $this->toBeRemovedNodeHash === spl_object_hash($node)) {
            $this->toBeRemovedNodeHash = null;

            return NodeTraverser::REMOVE_NODE;
        }

        $objectHash = spl_object_hash($node);
        return $this->nodesToReturn[$objectHash] ?? $node;
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
     * @param callable(Node $node): (Node|null|int) $callable
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

        $newNode->setAttribute(AttributeKey::PHP_DOC_INFO, $oldNode->getAttribute(AttributeKey::PHP_DOC_INFO));
        if (! $newNode instanceof Nop) {
            $newNode->setAttribute(AttributeKey::COMMENTS, $oldNode->getAttribute(AttributeKey::COMMENTS));
        }
    }

    /**
     * @param Arg[] $currentArgs
     * @param Arg[] $appendingArgs
     * @return Arg[]
     */
    protected function appendArgs(array $currentArgs, array $appendingArgs): array
    {
        foreach ($appendingArgs as $appendingArg) {
            $currentArgs[] = new Arg($appendingArg->value);
        }

        return $currentArgs;
    }

    /**
     * @param Node|Node[] $refactoredNode
     */
    private function postRefactorProcess(Node $originalNode, Node|array|int $refactoredNode): Node
    {
        /** @var non-empty-array<Node>|Node $refactoredNode */
        $this->createdByRuleDecorator->decorate($refactoredNode, $originalNode, static::class);

        $rectorWithLineChange = new RectorWithLineChange(static::class, $originalNode->getLine());
        $this->file->addRectorClassWithLine($rectorWithLineChange);

        /** @var MutatingScope|null $currentScope */
        $currentScope = $originalNode->getAttribute(AttributeKey::SCOPE);
        $filePath = $this->file->getFilePath();

        // search "infinite recursion" in https://github.com/nikic/PHP-Parser/blob/master/doc/component/Walking_the_AST.markdown
        $originalNodeHash = spl_object_hash($originalNode);

        if (is_array($refactoredNode)) {
            $firstNode = current($refactoredNode);
            $this->mirrorComments($firstNode, $originalNode);

            $this->refreshScopeNodes($refactoredNode, $filePath, $currentScope);

            // will be replaced in leaveNode() the original node must be passed
            $this->nodesToReturn[$originalNodeHash] = $refactoredNode;

            return $originalNode;
        }

        $this->refreshScopeNodes($refactoredNode, $filePath, $currentScope);

        $this->nodesToReturn[$originalNodeHash] = $refactoredNode;
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

    /**
     * @param class-string<Node> $nodeClass
     */
    private function isMatchingNodeType(string $nodeClass): bool
    {
        foreach ($this->getNodeTypes() as $nodeType) {
            if (is_a($nodeClass, $nodeType, true)) {
                return true;
            }
        }

        return false;
    }

    private function shouldSkipCurrentNode(Node $node): bool
    {
        $filePath = $this->file->getFilePath();
        if ($this->skipper->shouldSkipElementAndFilePath($this, $filePath)) {
            return true;
        }

        return $this->rectifiedAnalyzer->hasRectified(static::class, $node);
    }

    private function printCurrentFileAndRule(): void
    {
        $relativeFilePath = $this->filePathHelper->relativePath($this->file->getFilePath());

        $this->rectorOutputStyle->writeln('[file] ' . $relativeFilePath);
        $this->rectorOutputStyle->writeln('[rule] ' . static::class);
    }

    private function printConsumptions(float $startTime, int $previousMemory): void
    {
        $elapsedTime = microtime(true) - $startTime;
        $currentTotalMemory = memory_get_peak_usage(true);

        $consumed = sprintf(
            '--- consumed %s, total %s, took %.2f s',
            BytesHelper::bytes($currentTotalMemory - $previousMemory),
            BytesHelper::bytes($currentTotalMemory),
            $elapsedTime
        );
        $this->rectorOutputStyle->writeln($consumed);
        $this->rectorOutputStyle->newLine(1);
    }
}
