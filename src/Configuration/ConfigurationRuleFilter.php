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
     * @param array<RectorInterface> $rectors
     * @return array<RectorInterface>
     */
    public function filter(array $rectors): array
    {
        if (!$this->configuration instanceof Configuration) {
            return $rectors;
        }

        $onlyRule = $this->configuration->getOnlyRule();
        if ($onlyRule !== null) {
            return $this->filterOnlyRule($rectors, $onlyRule);
        }

        return $rectors;
    }

    /**
     * @param array<RectorInterface> $rectors
     * @return array<RectorInterface>
     */
    public function filterOnlyRule(array $rectors, string $onlyRule): array
    {
        $activeRectors = [];
        foreach ($rectors as $rector) {
            if ($rector instanceof $onlyRule) {
                $activeRectors[] = $rector;
            }
        }

        return $activeRectors;
    }
}
