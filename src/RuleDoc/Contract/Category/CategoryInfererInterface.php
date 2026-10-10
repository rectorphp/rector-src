<?php

declare(strict_types=1);

namespace Rector\RuleDoc\Contract\Category;

use Rector\Doc\RuleDefinition;

/**
 * @api
 */
interface CategoryInfererInterface
{
    public function infer(RuleDefinition $ruleDefinition): ?string;
}
