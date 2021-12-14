<?php

declare(strict_types=1);

namespace Rector\RuleDocGenerator\Category;

use Nette\Utils\Strings;
use Rector\Core\Exception\ShouldNotHappenException;
use Symplify\RuleDocGenerator\Contract\Category\CategoryInfererInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RectorCategoryInferer implements CategoryInfererInterface
{
    public function infer(RuleDefinition $ruleDefinition): ?string
    {
        $matches = Strings::match($ruleDefinition->getRuleClass(), self::RECTOR_CATEGORY_REGEX);
        if (! isset($matches[self::CATEGORY])) {
            $message = sprintf('Category for "%s" could not be resolved', $ruleDefinition->getRuleClass());
            throw new ShouldNotHappenException($message);
        }

        return $matches[self::CATEGORY];
    }
}
