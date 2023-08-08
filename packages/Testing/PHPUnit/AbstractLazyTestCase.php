<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit;

use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\DependencyInjection\LazyContainerFactory;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use Webmozart\Assert\Assert;

abstract class AbstractLazyTestCase extends TestCase
{
    private static ?Container $container = null;

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

    protected static function getContainer(): Container
    {
        if (! self::$container instanceof Container) {
            $lazyContainerFactory = new LazyContainerFactory();
            self::$container = $lazyContainerFactory->create();
        }

        return self::$container;
    }

    /**
     * @api soon be used
     */
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
}
