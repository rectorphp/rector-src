<?php

declare(strict_types=1);

namespace Rector\Bridge;

use Rector\Doctrine\Set\SetProvider\DoctrineSetProvider;
use Rector\PHPUnit\Set\SetProvider\PHPUnitSetProvider;
use Rector\Set\Contract\SetInterface;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\SetProvider\CoreSetProvider;
use Rector\Set\SetProvider\PHPSetProvider;
use Rector\Set\ValueObject\ComposerTriggeredSet;
use Rector\Symfony\Set\SetProvider\SymfonySetProvider;
use Rector\Symfony\Set\SetProvider\TwigSetProvider;

/**
 * @api
 * @experimental since 1.1.2
 * Utils class to ease building bridges by 3rd-party tools
 */
final readonly class SetProviderCollector
{
    /**
     * @var SetProviderInterface[]
     */
    private array $setProviders;

    /**
     * @param SetProviderInterface[] $extraSetProviders
     */
    public function __construct(array $extraSetProviders = [])
    {
        $setProviders = [
            // register all known set providers here
            new PHPSetProvider(),
            new CoreSetProvider(),
            new PHPUnitSetProvider(),
            new SymfonySetProvider(),
            new DoctrineSetProvider(),
            new TwigSetProvider(),
        ];

        $this->setProviders = array_merge($setProviders, $extraSetProviders);
    }

    /**
     * @return array<SetProviderInterface>
     */
    public function provide(): array
    {
        return $this->setProviders;
    }

    /**
     * @return array<SetInterface>
     */
    public function provideSets(): array
    {
        $sets = [];

        foreach ($this->setProviders as $setProvider) {
            $sets = array_merge($sets, $setProvider->provide());
        }

        return $sets;
    }

    /**
     * @return array<ComposerTriggeredSet>
     */
    public function provideComposerTriggeredSets(): array
    {
        return array_filter(
            $this->provideSets(),
            fn (SetInterface $set): bool => $set instanceof ComposerTriggeredSet
        );
    }
}
