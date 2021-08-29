<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\Stmt;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\While_;
use PhpParser\Node\Stmt\Foreach_;
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
        ClassMethod::class,
        If_::class,
        Do_::class,
        While_::class,
        For_::class,
        Foreach_::class,
    ];

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
        if (! in_array($node::class, self::STMTS_TO_HAVE_NEXT_NEWLINE, true)) {
            return null;
        }

        $nextNode = $node->getAttribute(AttributeKey::NEXT_NODE);
        if ($nextNode instanceof Nop) {
            return null;
        }

        if (! $nextNode instanceof Nop) {
            return null;
        }

        $currentStatement = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        if ($currentStatement instanceof Nop) {
            return null;
        }

        $this->addNodeAfterNode(new Nop(), $node);
        return $node;
    }
}
