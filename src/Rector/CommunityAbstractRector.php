<?php

declare(strict_types=1);

namespace Rector\Rector;

use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;

/**
 * DocumentedRuleInterface is not removed for community to allow generate docs
 */
abstract class CommunityAbstractRector extends AbstractRector implements DocumentedRuleInterface
{
}
