<?php

declare(strict_types=1);

namespace Rector\Tests\ChangesReporting\Annotation\AppliedRectorsChangelogResolver\Source;

use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md
 */
final class RectorWithChangelog extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Foo', []);
    }

    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(\PhpParser\Node $node)
    {

    }
}
