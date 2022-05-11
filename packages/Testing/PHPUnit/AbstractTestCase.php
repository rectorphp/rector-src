<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Kernel\RectorKernel;
use Webmozart\Assert\Assert;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @var array<string, RectorKernel>
     */
    private static array $kernelsByHash = [];

    private static ?ContainerInterface $currentContainer = null;

    protected function boot(): void
    {
        $this->bootFromConfigFiles([]);
    }

    /**
     * @param string[] $configFiles
     */
    protected function bootFromConfigFiles(array $configFiles): void
    {
        $configsHash = $this->createConfigsHash($configFiles);

        if (isset(self::$kernelsByHash[$configsHash])) {
            $rectorKernel = self::$kernelsByHash[$configsHash];
            self::$currentContainer = $rectorKernel->getContainer();
        } else {
            $rectorKernel = new RectorKernel();
            $container = $rectorKernel->createFromConfigs($configFiles);

            self::$kernelsByHash[$configsHash] = $rectorKernel;
            self::$currentContainer = $container;
        }
    }

    /**
     * Syntax-sugar to remove static
     *
     * @template T of object
     * @param class-string<T> $type
     */
    protected function getService(string $type): \T
    {
        if (self::$currentContainer === null) {
            throw new ShouldNotHappenException('First, create container with "bootWithConfigFileInfos([...])"');
        }

        $object = self::$currentContainer->get($type);
        if ($object === null) {
            $message = sprintf('Service "%s" was not found', $type);
            throw new ShouldNotHappenException($message);
        }

        return $object;
    }

    /**
     * @param string[] $configFiles
     */
    private function createConfigsHash(array $configFiles): string
    {
        Assert::allFile($configFiles);
        Assert::allString($configFiles);

        $configHash = '';
        foreach ($configFiles as $configFile) {
            $configHash .= md5_file($configFile);
        }

        return $configHash;
    }
}
