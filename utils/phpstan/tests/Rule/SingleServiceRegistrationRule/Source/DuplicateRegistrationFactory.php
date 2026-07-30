<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\SingleServiceRegistrationRule\Source;

use Illuminate\Container\Container;
use Rector\Config\RectorConfig;
use Rector\Contract\DependencyInjection\ResettableInterface;

final class DuplicateRegistrationFactory
{
    /**
     * @var array<class-string>
     */
    private const array SOME_VISITOR_CLASSES = [];

    public function create(): RectorConfig
    {
        $rectorConfig = new RectorConfig();

        $this->registerTagged($rectorConfig, self::SOME_VISITOR_CLASSES, SomeTagInterface::class);

        $rectorConfig->singleton(SomeResettableService::class);
        $rectorConfig->tag(SomeOtherService::class, SomeTagInterface::class);

        $this->registerTagged($rectorConfig, self::SOME_VISITOR_CLASSES, SomeTagInterface::class);

        $rectorConfig->tag(SomeOtherService::class, SomeTagInterface::class);

        $rectorConfig->tag(SomeResettableService::class, ResettableInterface::class);

        return $rectorConfig;
    }

    /**
     * @param array<class-string> $classes
     * @param class-string $tagInterface
     */
    private function registerTagged(Container $container, array $classes, string $tagInterface): void
    {
        foreach ($classes as $class) {
            $container->singleton($class);
            $container->tag($class, $tagInterface);
        }
    }
}
