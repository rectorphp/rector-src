<?php

declare(strict_types=1);

namespace Rector\Utils\RuleDocGenerator;

use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Symplify\RuleDocGenerator\Contract\RuleOutFilterInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleClassWithFilePath;

final class PostRectorOutFilter implements RuleOutFilterInterface
{
    /**
     * @param RuleClassWithFilePath[] $ruleClassWithFilePath
     * @return RuleClassWithFilePath[]
     */
    public function filter(array $ruleClassWithFilePath): array
    {
        return array_filter(
            $ruleClassWithFilePath,
            static fn (RuleClassWithFilePath $ruleClassWithFilePath): bool =>
                ! is_a($ruleClassWithFilePath->getClass(), PostRectorInterface::class, true)
        );
    }
}
