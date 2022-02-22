<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\TryCatch;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\Node\Stmt\TryCatch;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\RemoveDeadTryCatchRectorTest
 */
final class RemoveDeadTryCatchRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove dead try/catch', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        try {
            // some code
        }
        catch (Throwable $throwable) {
            throw $throwable;
        }
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [TryCatch::class];
    }

    /**
     * @param TryCatch $node
     * @return null|TryCatch|Stmt[]
     */
    public function refactor(Node $node): null|TryCatch|array
    {
        foreach ($node->catches as $catch) {
            if ($catch->stmts === []) {
                continue;
            }

            if (! isset($catch->stmts[0])) {
                return null;
            }

            if (count($catch->stmts) !== 1) {
                return null;
            }

            if ($catch->stmts[0] instanceof Nop) {
                continue;
            }

            if (! $catch->stmts[0] instanceof Throw_) {
                return null;
            }
        }

        if ($node->finally instanceof Finally_ && count($node->finally->stmts) === 1 && isset($node->finally->stmts[0]) && $node->finally->stmts[0] instanceof Nop) {
            $this->removeNode($node);
            return $node;
        }

        if ($node->finally instanceof Finally_ && $node->finally->stmts !== []) {
            return null;
        }

        if ($node->stmts === []) {
            $this->removeNode($node);
            return $node;
        }

        if (count($node->stmts) === 1 && isset($node->stmts[0]) && $node->stmts[0] instanceof Nop) {
            $this->removeNode($node);
            return $node;
        }

        return $node->stmts;
    }
}
