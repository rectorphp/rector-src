<?php

declare(strict_types=1);

namespace Rector\Bridge;

use Rector\Config\RectorConfig;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Contract\Rector\RectorInterface;
use ReflectionProperty;
use Webmozart\Assert\Assert;

/**
 * @api
 * @experimental since 1.1.2
 * Utils class to ease building bridges by 3rd-party tools
 */
final class SetRectorsResolver
{
    /**
     * @return array<class-string<RectorInterface>>
     */
    public function resolveFromFilePathForPhpLevel(string $configFilePath): array
    {
        $rectorClasses = $this->resolveFromFilePath($configFilePath);

        $nonConfigurableRectorClasses = array_filter(
            $rectorClasses,
            fn (string $rectorClass): bool => ! is_a($rectorClass, ConfigurableRectorInterface::class, true)
        );

        // revert to start from the lowest level
        return array_reverse($nonConfigurableRectorClasses);
    }

    /**
     * @return array<class-string<RectorInterface>>
     */
    public function resolveFromFilePath(string $configFilePath): array
    {
        Assert::fileExists($configFilePath);

        $rectorConfig = new RectorConfig();

        /** @var callable $configCallable */
        $configCallable = require $configFilePath;
        $configCallable($rectorConfig);

        // get tagged class-names
        $tagsReflectionProperty = new ReflectionProperty($rectorConfig, 'tags');
        $tags = $tagsReflectionProperty->getValue($rectorConfig);

        $rectorClasses = $tags[RectorInterface::class] ?? [];

        // avoid sorting to keep original natural order for levels

        return array_unique($rectorClasses);
    }
}
