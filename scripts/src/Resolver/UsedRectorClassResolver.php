<?php

declare(strict_types=1);

namespace Rector\Scripts\Resolver;

use Rector\Bridge\SetRectorsResolver;

final class UsedRectorClassResolver
{
    /**
     * @param string[] $rectorSetFiles
     * @return string[]
     */
    public function resolve(array $rectorSetFiles): array
    {
        $setRectorsResolver = new SetRectorsResolver();
        $rulesConfiguration = $setRectorsResolver->resolveFromFilePathsIncludingConfiguration($rectorSetFiles);

        $usedRectorRules = [];
        foreach ($rulesConfiguration as $ruleConfiguration) {
            $usedRectorRules[] = is_string($ruleConfiguration) ? $ruleConfiguration : array_keys($ruleConfiguration)[0];
        }

        sort($usedRectorRules);

        return array_unique($usedRectorRules);
    }
}
