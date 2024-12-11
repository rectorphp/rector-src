<?php

declare(strict_types=1);

namespace Rector\Rector;

use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;

/**
 * Extending this class will allow community to generate docs based on definition by implements getRuleDefinition() method
 */
abstract class CommunityAbstractRector extends AbstractRector implements DocumentedRuleInterface
{
}
