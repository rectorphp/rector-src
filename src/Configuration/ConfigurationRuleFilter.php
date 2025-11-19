<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\Contract\Rector\RectorInterface;
use Rector\ValueObject\Configuration;

/**
 * Modify available rector rules based on the configuration options
 */
final class ConfigurationRuleFilter
{
    private ?Configuration $configuration = null;

    public function setConfiguration(Configuration $configuration): void
    {
        $this->configuration = $configuration;
    }

    /**
     * @param list<RectorInterface> $rectors
     * @return list<RectorInterface>
     */
    public function filter(array $rectors): array
    {
        if (! $this->configuration instanceof Configuration) {
            return $rectors;
        }

        $onlyRule = $this->configuration->getOnlyRule();
        if ($onlyRule !== null) {
            foreach ($rectors as $rector) {
                if ($rector instanceof $onlyRule) {
                    return [$rector];
                }
            }
        }

        return $rectors;
    }

    /**
     * @param list<RectorInterface> $rectors
     * @return list<RectorInterface>
     */
    public function filterOnlyRule(array $rectors, string $onlyRule): array
    {
        $activeRectors = [];
        foreach ($rectors as $rector) {
            if ($rector instanceof $onlyRule) {
                return [$rector];
            }
        }

        return $activeRectors;
    }
}
