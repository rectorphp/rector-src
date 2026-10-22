<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\RegisterRectorRule\Source;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;

final class SiblingRegisteredRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rule registered in a sibling package config', [new CodeSample('before', 'after')]);
    }

    public function getNodeTypes(): array
    {
        return [Node::class];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }
}
