<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector\Fixture;

use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleClassWithFilePath;

final class SkipNegatedBoolean
{
    public function run(RuleClassWithFilePath $ruleClassWithFilePath)
    {
        return ! is_a($ruleClassWithFilePath->getClass(), PostRectorInterface::class, true);
    }
}
