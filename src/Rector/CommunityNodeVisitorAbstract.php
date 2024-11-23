<?php

declare(strict_types=1);

namespace Rector\Rector;

use PhpParser\NodeVisitorAbstract;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

// used for inject to community rule
// to avoid error when using #[\Override]
abstract class CommunityNodeVisitorAbstract extends NodeVisitorAbstract
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }
}
