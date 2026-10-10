<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper\Fixture\Element;

use PhpParser\Node;
use Rector\RuleDoc\RuleDefinition;

final class NotSkippedClass implements \Rector\Contract\Rector\RectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        // TODO: Implement getRuleDefinition() method.
    }

    public function getNodeTypes(): array
    {
        // TODO: Implement getNodeTypes() method.
    }

    public function refactor(Node $node)
    {
        // TODO: Implement refactor() method.
    }
}
