<?php

declare(strict_types=1);

namespace Rector\Tests\Reporting\Source;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;

final class MakeClassFinalRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Test rule that makes a non-final class final', [
            new CodeSample('class SomeClass {}', 'final class SomeClass {}'),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node->isFinal() || $node->isAbstract()) {
            return null;
        }

        $node->flags |= Modifiers::FINAL;
        return $node;
    }
}
