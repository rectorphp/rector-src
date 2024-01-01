<?php

declare(strict_types=1);

namespace Rector\Tests\ChangesReporting\Annotation\AppliedRectorsChangelogResolver\Source;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RectorWithOutChangelog extends AbstractRector
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
