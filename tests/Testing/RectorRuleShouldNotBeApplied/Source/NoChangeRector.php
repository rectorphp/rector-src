<?php

declare(strict_types=1);

namespace Rector\Tests\Testing\RectorRuleShouldNotBeApplied\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NoChangeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('no change', []);
    }

    public function getNodeTypes(): array
    {
        return [
            Class_::class,
        ];
    }

    public function refactor(Node $node)
    {
        return $node;
    }
}
