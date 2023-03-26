<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\Stmt;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\While_;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Collector\NodesToRemoveCollector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\Stmt\NewlineAfterStatementRector\NewlineAfterStatementRectorTest
 */
final class NewlineAfterStatementRector extends AbstractRector
{
    /**
     * @var array<class-string<Node>>
     */
    private const STMTS_TO_HAVE_NEXT_NEWLINE = [
        ClassMethod::class,
        Function_::class,
        Property::class,
        If_::class,
        Foreach_::class,
        Do_::class,
        While_::class,
        For_::class,
        ClassConst::class,
        TryCatch::class,
        Class_::class,
        Trait_::class,
        Interface_::class,
        Switch_::class,
    ];

    public function __construct(
        private readonly NodesToRemoveCollector $nodesToRemoveCollector
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add new line after statements to tidify code',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function first()
    {
    }
    public function second()
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function first()
    {
    }

    public function second()
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
        return [StmtsAwareInterface::class, ClassLike::class];
    }

    /**
     * @param StmtsAwareInterface|ClassLike $node
     */
    public function refactor(Node $node): null|StmtsAwareInterface|ClassLike
    {
        return $this->processAddNewLine($node, false);
    }

    private function processAddNewLine(
        StmtsAwareInterface|ClassLike $node,
        bool $hasChanged
    ): null|StmtsAwareInterface|ClassLike {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $key => $stmt) {
            if (! isset($node->stmts[$key + 1])) {
                break;
            }

            $nextStmt = $node->stmts[$key + 1];
            if ($this->shouldSkip($nextStmt, $stmt)) {
                continue;
            }

            $endLine = $stmt->getEndLine();
            $line = $nextStmt->getStartLine();
            $rangeLine = $line - $endLine;

            if ($rangeLine > 1) {
                $rangeLine = $this->resolveRangeLineFromComment($rangeLine, $line, $endLine, $nextStmt);
            }

            // skip same line or < 0 that cause infinite loop or crash
            if ($rangeLine <= 0) {
                continue;
            }

            if ($rangeLine > 1) {
                continue;
            }

            array_splice($node->stmts, $key + 1, 0, [new Nop()]);

            $hasChanged = true;

            return $this->processAddNewLine($node, $hasChanged);
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function resolveRangeLineFromComment(int|float $rangeLine, int $line, int $endLine, Stmt $nextStmt): int|float
    {
        /** @var Comment[]|null $comments */
        $comments = $nextStmt->getAttribute(AttributeKey::COMMENTS);

        if ($this->hasNoComment($comments)) {
            return $rangeLine;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($nextStmt);
        if ($phpDocInfo->hasChanged()) {
            return $rangeLine;
        }

        /** @var Comment[] $comments */
        $line = $comments[0]->getStartLine();
        return $line - $endLine;
    }

    /**
     * @param Comment[]|null $comments
     */
    private function hasNoComment(?array $comments): bool
    {
        if ($comments === null) {
            return true;
        }

        return ! isset($comments[0]);
    }

    private function shouldSkip(Stmt $nextStmt, Stmt $stmt): bool
    {
        if (! in_array($stmt::class, self::STMTS_TO_HAVE_NEXT_NEWLINE, true)) {
            return true;
        }

        if (in_array($nextStmt::class, [Else_::class, ElseIf_::class, Catch_::class, Finally_::class], true)) {
            return true;
        }

        if ($this->nodesToRemoveCollector->isNodeRemoved($stmt)) {
            return true;
        }

        $parentCurrentNode = $stmt->getAttribute(AttributeKey::PARENT_NODE);
        $parentnextStmt = $nextStmt->getAttribute(AttributeKey::PARENT_NODE);

        return $parentnextStmt !== $parentCurrentNode;
    }
}
