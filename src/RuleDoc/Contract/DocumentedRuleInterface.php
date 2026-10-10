<?php

declare(strict_types=1);

namespace Rector\RuleDoc\Contract;

use Rector\Doc\RuleDefinition;

/**
 * @api
 */
interface DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition;
}
