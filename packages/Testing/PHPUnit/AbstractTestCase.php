<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit;

use PHPUnit\Framework\TestCase;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Kernel\RectorKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symplify\SmartFileSystem\SmartFileInfo;
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
     * @deprecated
     * Use @see bootFromConfigFiles() instead
     *
     * @param SmartFileInfo[] $configFileInfos
     */
    protected function bootFromConfigFileInfos(array $configFileInfos): void
    {
        $configFiles = array_map(function (SmartFileInfo $smartFileInfo) {
            return $smartFileInfo->getRealPath();
        }, $configFileInfos);

        $this->bootFromConfigFiles($configFiles);
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
            $rectorKernel = new RectorKernel('test_' . $configsHash, true, $configFiles);
            $rectorKernel->boot();

            self::$kernelsByHash[$configsHash] = $rectorKernel;
            self::$currentContainer = $rectorKernel->getContainer();
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
