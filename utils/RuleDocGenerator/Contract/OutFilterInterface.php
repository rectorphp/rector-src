<?php

declare(strict_types=1);

namespace Rector\Utils\RuleDocGenerator\Contract;

use Symplify\RuleDocGenerator\ValueObject\RuleClassWithFilePath;

interface OutFilterInterface
{
    /**
     * @param RuleClassWithFilePath[] $ruleClassWithFilePath
     * @return RuleClassWithFilePath[]
     */
    public function filter(array $ruleClassWithFilePath): array;
}
