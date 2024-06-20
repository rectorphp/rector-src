<?php

declare(strict_types=1);

namespace Rector\Set;

use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\ComposerTriggeredSet;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Set\SetCollector\SetCollectorTest
 */
final readonly class SetCollector
{
    /**
     * @param SetProviderInterface[] $setProviders
     */
    public function __construct(
        private array $setProviders
    ) {
        Assert::allIsInstanceOf($setProviders, SetProviderInterface::class);
    }

    /**
     * @return ComposerTriggeredSet[]
     */
    public function matchComposerTriggered(string $groupName): array
    {
        $matchedSets = [];

        foreach ($this->setProviders as $setProvider) {
            foreach ($setProvider->provide() as $set) {
                if (! $set instanceof ComposerTriggeredSet) {
                    continue;
                }

                if ($set->getGroupName() === $groupName) {
                    $matchedSets[] = $set;
                }
            }
        }

        return $matchedSets;
    }
}
