<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\ClassLike;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Nop;
use Rector\Comments\CommentResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector\NewlineBetweenClassLikeStmtsRectorTest
 */
final class NewlineBetweenClassLikeStmtsRector extends AbstractRector
{
    public function __construct(
        private readonly CommentResolver $commentResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add new line space between class constants, properties and class methods to make it more readable',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public const NAME = 'name';
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
final class SomeClass
{
    public const NAME = 'name';

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
        return [ClassLike::class];
    }

    /**
     * @param ClassLike $node
     */
    public function refactor(Node $node): ?ClassLike
    {
        return $this->processAddNewLine($node, false);
    }

    private function processAddNewLine(ClassLike $node, bool $hasChanged, int $jumpToKey = 0): null|ClassLike
    {
        $totalKeys = array_key_last($node->stmts);

        for ($key = $jumpToKey; $key < $totalKeys; ++$key) {
            if (! isset($node->stmts[$key], $node->stmts[$key + 1])) {
                break;
            }

            $stmt = $node->stmts[$key];
            $nextStmt = $node->stmts[$key + 1];

            $endLine = $stmt->getEndLine();
            $rangeLine = $nextStmt->getStartLine() - $endLine;

            if ($rangeLine > 1) {
                $rangeLine = $this->commentResolver->resolveRangeLineFromComment($rangeLine, $endLine, $nextStmt);
            }

            // skip same line or < 0 that cause infinite loop or crash
            if ($rangeLine !== 1) {
                continue;
            }

            array_splice($node->stmts, $key + 1, 0, [new Nop()]);

            $hasChanged = true;

            // iterate next
            return $this->processAddNewLine($node, $hasChanged, $key + 2);
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
