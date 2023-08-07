<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit;

abstract class AbstractTestCase extends AbstractLazyTestCase
{
    /**
     * @deprecated only for BC
     */
    protected function boot(): void
    {
    }

    /**
     * @param string[] $configFiles
     */
    protected function bootFromConfigFiles(array $configFiles): void
    {
        $container = self::getContainer();

        foreach ($configFiles as $configFile) {
            $callable = require $configFile;
            $callable($container);
        }
    }

    /**
     * Syntax-sugar to remove static
     * @deprecated Only for BC
     *
     * @template T of object
     * @param class-string<T> $type
     * @return T
     */
    protected function getService(string $type): object
    {
        return $this->make($type);
    }
}
