<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Kernel\RectorKernel;
use Rector\Core\Util\FileHasher;
use Throwable;

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
        $fileHasher = new FileHasher();
        $configsHash = $fileHasher->hashFiles($configFiles);

        if (isset(self::$kernelsByHash[$configsHash])) {
            $rectorKernel = self::$kernelsByHash[$configsHash];
            self::$currentContainer = $rectorKernel->getContainer();
        } else {
            $rectorKernel = new RectorKernel();
            $containerBuilder = $rectorKernel->createFromConfigs($configFiles);

            self::$kernelsByHash[$configsHash] = $rectorKernel;
            self::$currentContainer = $containerBuilder;
        }
    }

    /**
     * Syntax-sugar to remove static
     *
     * @template T of object
     * @param class-string<T> $type
     * @return T
     */
    protected function getService(string $type): object
    {
        if (! self::$currentContainer instanceof ContainerInterface) {
            throw new ShouldNotHappenException(
                'First, create container with "boot()" or "bootWithConfigFileInfos([...])"'
            );
        }

        try {
            $object = self::$currentContainer->get($type);
        } catch (Throwable $throwable) {
            // clear compiled container cache, to trigger re-discovery
            RectorKernel::clearCache();

            throw $throwable;
        }

        if ($object === null) {
            $message = sprintf('Service "%s" was not found', $type);
            throw new ShouldNotHappenException($message);
        }

        return $object;
    }
}
