<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;
use PhpParser\Comment\Doc;
use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\UseItem;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use Webmozart\Assert\Assert;

require __DIR__ . '/../vendor/autoload.php';

// load file contents from vendor
// modify clas name + namespace
// add it here as "AbstractImmutableNodeTraverser.php"

$vendorNodeTraverserFilePath = __DIR__ . '/../vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php';

$parserFactory = new ParserFactory();
$parser = $parserFactory->createForHostVersion();
$stmts = $parser->parse(FileSystem::read($vendorNodeTraverserFilePath));

Assert::isArray($stmts);
Assert::allIsInstanceOf($stmts, Stmt::class);

$originalStmts = $stmts;

final class ReplaceForeachThisVisitorNodeVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly string $nodeName
    ) {

    }

    /**
     * @return Stmt[]|null
     */
    public function enterNode(Node $node): ?array
    {
        if (! $node instanceof Foreach_) {
            return null;
        }

        if (! $node->expr instanceof PropertyFetch) {
            return null;
        }

        $foreachedPropertyFetch = $node->expr;
        if (! $foreachedPropertyFetch->var instanceof Variable) {
            return null;
        }

        if ($foreachedPropertyFetch->var->name !== 'this') {
            return null;
        }

        if (! $foreachedPropertyFetch->name instanceof Identifier) {
            return null;
        }

        if ($foreachedPropertyFetch->name->toString() !== 'visitors') {
            return null;
        }

        // replace $this->visitors with $currentNodeVisitors
        $currentNodeVisitorsVariable = new Variable(ImmutableNodeTraverserName::CURRENT_NODE_VISITORS);
        $node->expr = $currentNodeVisitorsVariable;

        // add before foreach: $currentNodeVisitors = $this->getVisitorsForNode($node);
        $assign = new Assign(
            $currentNodeVisitorsVariable,
            new MethodCall(
                new Variable('this'),
                ImmutableNodeTraverserName::GET_VISITORS_FOR_NODE_METHOD,
                [new Arg(new Variable($this->nodeName))]
            )
        );

        return [new Expression($assign), $node];
    }
}

final class ReplaceThisVisitorsWithThisGetVisitorsNodeVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node): ?ClassMethod
    {
        if (! $node instanceof ClassMethod || $node->stmts === null) {
            return null;
        }

        if (! in_array($node->name->toString(), ['traverseArray', 'traverseNode'])) {
            return null;
        }

        $traverseArrayNodeName = $node->name->toString() === 'traverseNode' ? 'subNode' : 'node';

        // handle foreach $this->visitors
        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new ReplaceForeachThisVisitorNodeVisitor($traverseArrayNodeName));

        $node->stmts = $nodeTraverser->traverse($node->stmts);

        return $node;
    }
}

final class RenameNamespaceNodeVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node): ?Namespace_
    {
        if (! $node instanceof Namespace_) {
            return null;
        }

        // add uses for PHPParser nodes as locations are now changed
        $uses = [
            new Use_([new UseItem(new Name(NodeTraverserInterface::class))]),
            new Use_([new UseItem(new Name(NodeVisitor::class))]),
            new Use_([new UseItem(new Name(Node::class))]),
        ];

        /** @var Stmt[] $newStmts */
        $newStmts = array_merge($uses, (array) $node->stmts);
        $node->stmts = $newStmts;

        $node->name = new Name('Rector\PhpParser\NodeTraverser');
        return $node;
    }
}

final class ReplaceThisVisitorsArrayDimFetchWithCurrentNodeVisitorsNodeVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof ArrayDimFetch) {
            return null;
        }

        if (! $node->var instanceof PropertyFetch) {
            return null;
        }

        $propertyFetch = $node->var;
        if (! $propertyFetch->var instanceof Variable) {
            return null;
        }

        if ($propertyFetch->var->name !== 'this') {
            return null;
        }

        if (! $propertyFetch->name instanceof Identifier) {
            return null;
        }

        if ($propertyFetch->name->toString() !== 'visitors') {
            return null;
        }

        if (! $node->dim instanceof Variable) {
            return null;
        }

        if ($node->dim->name !== 'visitorIndex') {
            return null;
        }

        $node->var = new Variable(ImmutableNodeTraverserName::CURRENT_NODE_VISITORS);

        return $node;
    }
}

final class DecorateClassNodeVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node): ?Class_
    {
        if (! $node instanceof Class_) {
            return null;
        }

        $node->flags |= Modifiers::ABSTRACT;
        $node->name = new Identifier('AbstractImmutableNodeTraverser');

        $getVisitorsForNodeClassMethod = new ClassMethod(ImmutableNodeTraverserName::GET_VISITORS_FOR_NODE_METHOD, [
            'flags' => Modifiers::PUBLIC | Modifiers::ABSTRACT,
            'params' => [new Param(var: new Variable('node'), type: new FullyQualified(Node::class))],
            'returnType' => new Name('array'),
            'stmts' => null,
        ]);

        // add @return NodeVisitor[] docblock
        $getVisitorsForNodeClassMethod->setDocComment(new Doc(<<<'DOC'
/**
 * @return NodeVisitor[]
 */
DOC
        ));

        $node->stmts[] = $getVisitorsForNodeClassMethod;

        return $node;
    }
}

$nodeTraverser = new NodeTraverser();

$nodeTraverser->addVisitor(new DecorateClassNodeVisitor());
$nodeTraverser->addVisitor(new ReplaceThisVisitorsWithThisGetVisitorsNodeVisitor());
$nodeTraverser->addVisitor(new ReplaceThisVisitorsArrayDimFetchWithCurrentNodeVisitorsNodeVisitor());
$nodeTraverser->addVisitor(new RenameNamespaceNodeVisitor());

$stmts = $nodeTraverser->traverse($stmts);

final class ImmutableNodeTraverserName
{
    /**
     * @var string
     */
    public const GET_VISITORS_FOR_NODE_METHOD = 'getVisitorsForNode';

    /**
     * @var string
     */
    public const CURRENT_NODE_VISITORS = 'currentNodeVisitors';
}

// print node traverser contents
$standard = new Standard();
$immutableNodeTraverserFileContents = $standard->printFormatPreserving($stmts, $originalStmts, $parser->getTokens());

// save the file
FileSystem::write(
    __DIR__ . '/../src/PhpParser/NodeTraverser/AbstractImmutableNodeTraverser.php',
    $immutableNodeTraverserFileContents
);

echo sprintf('New file "%s" was created', 'src/PhpParser/NodeTraverser/AbstractImmutableNodeTraverser.php') . PHP_EOL;
