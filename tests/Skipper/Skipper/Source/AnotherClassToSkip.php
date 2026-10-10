<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper\Source;

use PhpParser\Node;
use Rector\Contract\Rector\RectorInterface;
use Rector\RuleDoc\RuleDefinition;

final class AnotherClassToSkip implements RectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixture rule used to assert path scoped skipping', []);
    }

    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node)
    {
        return null;
    }
}
