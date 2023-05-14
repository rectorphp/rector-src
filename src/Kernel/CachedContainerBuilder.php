<?php

declare(strict_types=1);

namespace Rector\Core\Kernel;

use Rector\Core\Exception\ShouldNotHappenException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * see https://symfony.com/doc/current/components/dependency_injection/compilation.html#dumping-the-configuration-for-performance
 */
final class CachedContainerBuilder
{
    public function __construct(
        private readonly string $cacheKey,
    ) {
    }

    /**
     * @param string[] $configFiles
     * @param callable(string[] $configFiles):ContainerBuilder $containerBuilderCallback
     */
    public function build(array $configFiles, string $hash, callable $containerBuilderCallback): ContainerInterface
    {
        $filesystem = new SmartFileSystem();
        $className = 'RectorKernel' . $hash;
        $file = sys_get_temp_dir() . '/rector/kernel-' . $this->cacheKey . '-' . $hash . '.php';

        if (file_exists($file)) {
            require_once $file;
            $className = '\\' . __NAMESPACE__ . '\\' . $className;
            $container = new $className();
            if (! $container instanceof ContainerInterface) {
                throw new ShouldNotHappenException();
            }
        } else {
            $container = ($containerBuilderCallback)($configFiles);

            $dumper = new PhpDumper($container);
            $dumpedContainer = $dumper->dump([
                'class' => $className,
                'namespace' => __NAMESPACE__,
            ]);
            if (! is_string($dumpedContainer)) {
                throw new ShouldNotHappenException();
            }

            $filesystem->dumpFile($file, $dumpedContainer);
        }

        return $container;
    }

    public function clearCache(): void
    {
        $dir = sys_get_temp_dir();
        if (! is_writable($dir)) {
            return;
        }

        $cacheFiles = glob($dir . '/rector/kernel-*.php');
        if ($cacheFiles === false) {
            return;
        }

        $smartFileSystem = new SmartFileSystem();
        $smartFileSystem->remove($cacheFiles);
    }
}
