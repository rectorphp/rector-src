<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\RegisterRectorRule\Source;

use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;

final class SkipConfigurableRector extends AbstractRector implements ConfigurableRectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Configurable rule', [new CodeSample('before', 'after')]);
    }

    public function getNodeTypes(): array
    {
        return [Node::class];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }

    public function configure(array $configuration): void
    {
    }
}
