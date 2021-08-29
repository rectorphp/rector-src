<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\Stmt;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\While_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\NodeTraverser;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\Stmt\NewlineAfterStatementRector\NewlineAfterStatementRectorTest
 */
final class NewlineAfterStatementRector extends AbstractRector
{
    private const STMTS_TO_HAVE_NEXT_NEWLINE = [
        //ClassMethod::class,
        Property::class,
        //Do_::class,
        //While_::class,
        //For_::class,
        //Foreach_::class,
    ];

    private array $stmtsHashed = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add new line after statements to tidify code',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function test()
    {
    }
    public function test2()
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function test()
    {
    }

    public function test2()
    {
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Stmt::class];
    }

    /**
     * @param Stmt $node
     */
    public function refactor(Node $node): ?Node
    {
        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parent instanceof Node) {
            return null;
        }

        $print = $this->betterStandardPrinter->prettyPrint([$parent]);
        $this->traverseNodesWithCallable($parent, function (Node $subNode) use ($node, $print, $parent): void {
            if (! in_array($node::class, self::STMTS_TO_HAVE_NEXT_NEWLINE, true)) {
                return;
            }

            if ($subNode !== $node) {
                return;
            }

            $hash = spl_object_hash($node);
            if (isset($this->stmtsHashed[$hash])) {
                return;
            }

            $nextNode = $subNode->getAttribute(AttributeKey::NEXT_NODE);
            if ($nextNode instanceof Nop) {
                return;
            }

            if (! $nextNode instanceof Node) {
                return;
            }

            $parent = $subNode->getAttribute(AttributeKey::PARENT_NODE);
            $newPrint = $this->betterStandardPrinter->prettyPrint([$parent]);
            if ($newPrint !== $print) {
                return;
            }

            $this->stmtsHashed[$hash] = true;
            $this->addNodeAfterNode(new Nop(), $subNode);
        });

        return $node;
    }
}
