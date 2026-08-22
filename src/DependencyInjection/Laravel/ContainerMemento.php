<?php

declare(strict_types=1);

namespace Rector\DependencyInjection\Laravel;

use Rector\Config\RectorConfig;
use Rector\Util\Reflection\PrivatesAccessor;

/**
 * Helper service to forget services from the entropy container, used to honour skip() on whole rules.
 */
final class ContainerMemento
{
    /**
     * @api
     * @see https://tomasvotruba.com/blog/removing-service-from-laravel-container-is-not-that-easy
     */
    public static function forgetTag(RectorConfig $rectorConfig, string $tagToForget): void
    {
        self::forgetByContract($rectorConfig, $tagToForget);
    }

    public static function forgetService(RectorConfig $rectorConfig, string $typeToForget): void
    {
        self::forgetByContract($rectorConfig, $typeToForget);
    }

    /**
     * Removes every registered factory and cached instance whose class is-a $contract, both from the
     * entropy container private storage and from the RectorConfig bookkeeping, so findByContract()
     * cannot resurrect them via reflection autowiring.
     */
    private static function forgetByContract(RectorConfig $rectorConfig, string $contract): void
    {
        $privatesAccessor = new PrivatesAccessor();

        $forgottenClasses = [];

        foreach (['serviceFactories', 'instances'] as $propertyName) {
            $privatesAccessor->propertyClosure(
                $rectorConfig,
                $propertyName,
                static function (array $items) use ($contract, &$forgottenClasses): array {
                    foreach (array_keys($items) as $class) {
                        if (! is_a($class, $contract, true)) {
                            continue;
                        }

                        unset($items[$class]);
                        $forgottenClasses[$class] = true;
                    }

                    return $items;
                }
            );
        }

        foreach (array_keys($forgottenClasses) as $forgottenClass) {
            $rectorConfig->forgetAbstract($forgottenClass);
        }

        // a rule registered as a plain singleton lives only in the bookkeeping until first resolved,
        // so clear it there as well
        $rectorConfig->forgetAbstract($contract);
    }
}
