<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\Stmt;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Do_;
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
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Contract\Rector\HTMLAverseRectorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\Stmt\NewlineAfterStatementRector\NewlineAfterStatementRectorTest
 */
final class NewlineAfterStatementRector extends AbstractRector implements HTMLAverseRectorInterface
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
        bool $hasChanged,
        int $jumpToKey = 0
    ): null|StmtsAwareInterface|ClassLike {
        if ($node->stmts === null) {
            return null;
        }

        $totalKeys = array_key_last($node->stmts);

        for ($key = $jumpToKey; $key < $totalKeys; ++$key) {
            if (! isset($node->stmts[$key], $node->stmts[$key + 1])) {
                break;
            }

            $stmt = $node->stmts[$key];
            $nextStmt = $node->stmts[$key + 1];

            if ($this->shouldSkip($stmt)) {
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
            return $this->processAddNewLine($node, $hasChanged, $key + 2);
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function resolveRangeLineFromComment(
        int|float $rangeLine,
        int $line,
        int $endLine,
        Stmt $nextStmt
    ): int|float {
        /** @var Comment[]|null $comments */
        $comments = $nextStmt->getAttribute(AttributeKey::COMMENTS);

        if ($this->hasNoComment($comments)) {
            return $rangeLine;
        }

        /** @var Comment[] $comments */
        $firstComment = $comments[0];

        $line = $firstComment->getStartLine();
        return $line - $endLine;
    }

    /**
     * @param Comment[]|null $comments
     */
    private function hasNoComment(?array $comments): bool
    {
        return $comments === null || $comments === [];
    }

    private function shouldSkip(Stmt $stmt): bool
    {
        return ! in_array($stmt::class, self::STMTS_TO_HAVE_NEXT_NEWLINE, true);
    }
}
