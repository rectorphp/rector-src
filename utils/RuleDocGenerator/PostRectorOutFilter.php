<?php
declare(strict_types=1);

namespace Rector\Utils\RuleDocGenerator;

use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\Utils\RuleDocGenerator\Contract\OutFilterInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleClassWithFilePath;

final class PostRectorOutFilter implements OutFilterInterface
{
    /**
     * @param \Symplify\RuleDocGenerator\ValueObject\RuleClassWithFilePath[] $ruleClassWithFilePath
     * @return \Symplify\RuleDocGenerator\ValueObject\RuleClassWithFilePath[]
     */
    public function filter(array $ruleClassWithFilePath): array
    {
        return array_filter(
            $ruleClassWithFilePath,
            function (RuleClassWithFilePath $ruleClassWithFilePath): bool {
                return ! is_a($ruleClassWithFilePath->getClass(), PostRectorInterface::class, true);
            }
        );
    }
}
