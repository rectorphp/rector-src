<?php

declare(strict_types=1);

namespace Rector\Core\Kernel;

use Throwable;
use UnitEnum;
use Rector\Core\Exception\Cache\StaleContainerCacheException;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CacheInvalidatingContainer implements ContainerInterface {
    public function __construct(
        private readonly ContainerInterface $container
    )
    {}

    public function set(string $id, ?object $service): void
    {
        $this->container->set($id, $service);
    }

    public function get(string $id, int $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE): ?object
    {
        try {
            return $this->container->get($id, $invalidBehavior);
        } catch (Throwable $throwable) {
            // clear compiled container cache, to trigger re-discovery
            RectorKernel::clearCache();

            throw new StaleContainerCacheException(
                'Container cache is outdated and was cleared. please re-run the command.',
                0,
                $throwable
            );
        }
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    public function initialized(string $id): bool
    {
        return $this->container->initialized($id);
    }

    /**
     * @return array<mixed>|bool|float|int|string|UnitEnum|null
     */
    public function getParameter(string $name)
    {
        return $this->container->getParameter($name);
    }

    public function hasParameter(string $name): bool
    {
        return $this->container->hasParameter($name);
    }

    /**
     * @param UnitEnum|float|array<mixed>|bool|int|string|null $value
     */
    public function setParameter(string $name, UnitEnum|float|array|bool|int|string|null $value): void
    {
        $this->container->setParameter($name, $value);
    }
}
