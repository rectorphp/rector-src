<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit;

use PHPUnit\Framework\TestCase;
<<<<<<< HEAD
=======
<<<<<<< HEAD
use Rector\Core\Contract\Rector\NonPhpRectorInterface;
=======
<<<<<<< HEAD
=======
use Rector\Config\RectorConfig;
>>>>>>> d3fd4c8350 (remove ValueObjectInliner as no longer used)
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
>>>>>>> 8c66710a92 (remove ValueObjectInliner as no longer used)
>>>>>>> a3f90f21fa (remove ValueObjectInliner as no longer used)
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\DependencyInjection\LazyContainerFactory;
<<<<<<< HEAD
<<<<<<< HEAD
use Rector\Core\Rector\AbstractRector;
=======
>>>>>>> d3fd4c8350 (remove ValueObjectInliner as no longer used)
=======
use Rector\Core\Rector\AbstractRector;
>>>>>>> 710d901055 (run just once)
use Rector\Core\Util\Reflection\PrivatesAccessor;
use Webmozart\Assert\Assert;

abstract class AbstractLazyTestCase extends TestCase
{
    private static ?RectorConfig $container = null;

    /**
     * @api
     * @param string[] $configFiles
     */
    protected function bootFromConfigFiles(array $configFiles): void
    {
        $container = self::getContainer();

        foreach ($configFiles as $configFile) {
            $configClosure = require $configFile;
            Assert::isCallable($configClosure);

            $configClosure($container);
        }
    }

    /**
     * @template TType as object
     * @param class-string<TType> $class
     * @return TType
     */
    protected function make(string $class): object
    {
        return self::getContainer()->make($class);
    }

    /**
     * @param string[] $configFiles
     */
    protected function bootFromConfigFiles(array $configFiles): void
    {
        $container = self::getContainer();

        foreach ($configFiles as $configFile) {
            $configClosure = require $configFile;
            Assert::isCallable($configClosure);

            $configClosure($container);
        }
    }

    protected static function getContainer(): RectorConfig
    {
        if (! self::$container instanceof RectorConfig) {
            $lazyContainerFactory = new LazyContainerFactory();
            self::$container = $lazyContainerFactory->create();
        }

        return self::$container;
    }

    protected function forgetRectorsRules(): void
    {
        $container = self::getContainer();

        // remove all tagged rules
        $privatesAccessor = new PrivatesAccessor();
        $privatesAccessor->propertyClosure($container, 'tags', function (array $tags): array {
            unset($tags[RectorInterface::class]);
            unset($tags[PhpRectorInterface::class]);
            unset($tags[ConfigurableRectorInterface::class]);

            return $tags;
        });

        $rectors = $container->tagged(RectorInterface::class);
        foreach ($rectors as $rector) {
            $container->offsetUnset(get_class($rector));
        }

        // remove after binding too, to avoid setting configuration over and over again

        $privatesAccessor->propertyClosure(
            $container,
            'afterResolvingCallbacks',
            function (array $afterResolvingCallbacks): array {
                foreach ($afterResolvingCallbacks as $key => $closure) {
                    if ($key === AbstractRector::class) {
                        continue;
                    }

                    if (is_a($key, RectorInterface::class, true)) {
                        unset($afterResolvingCallbacks[$key]);
                    }
                }

                return $afterResolvingCallbacks;
            }
        );
    }

    /**
     * @api soon be used
     */
    protected function forgetRectorsRules(): void
    {
        $container = self::getContainer();

        // 1. forget instance first! then remove tags
        $rectors = $container->tagged(RectorInterface::class);
        foreach ($rectors as $rector) {
            $container->offsetUnset($rector::class);
        }

        // 2. remove all tagged rules
        $privatesAccessor = new PrivatesAccessor();
        $privatesAccessor->propertyClosure($container, 'tags', static function (array $tags): array {
            unset($tags[RectorInterface::class]);
            unset($tags[PhpRectorInterface::class]);
            return $tags;
        });

        $rectors = $container->tagged(RectorInterface::class);
        foreach ($rectors as $rector) {
            $container->offsetUnset($rector::class);
        }

        // 3. remove after binding too, to avoid setting configuration over and over again
        $privatesAccessor->propertyClosure(
            $container,
            'afterResolvingCallbacks',
            static function (array $afterResolvingCallbacks): array {
                foreach (array_keys($afterResolvingCallbacks) as $key) {
                    if ($key === AbstractRector::class) {
                        continue;
                    }

                    if (is_a($key, RectorInterface::class, true)) {
                        unset($afterResolvingCallbacks[$key]);
                    }
                }

                return $afterResolvingCallbacks;
            }
        );
    }
}
