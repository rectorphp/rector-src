<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\RegisterRectorRule\Source;

use PhpParser\Node;
use Rector\Configuration\Deprecation\Contract\DeprecatedInterface;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;

final class SkipDeprecatedRector extends AbstractRector implements DeprecatedInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Deprecated rule', [new CodeSample('before', 'after')]);
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
