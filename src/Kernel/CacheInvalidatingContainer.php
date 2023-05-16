<?php

declare(strict_types=1);

namespace Rector\Core\Kernel;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CacheInvalidatingContainer implements ContainerInterface {
    public function __construct(
        private readonly ContainerInterface $wrapped
    )
    {}

    public function set(string $id, ?object $service)
    {
        $this->wrapped->set($id, $service);
    }

    public function get(string $id, int $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE): ?object
    {
        try {
            return $this->wrapped->get($id, $invalidBehavior);
        } catch (\Throwable $e) {
            RectorKernel::clearCache();

            throw $e;
        }
    }

    public function has(string $id): bool
    {
        return $this->wrapped->has($id);
    }

    public function initialized(string $id): bool
    {
        return $this->wrapped->initialized($id);
    }

    public function getParameter(string $name)
    {
        return $this->wrapped->getParameter($name);
    }

    public function hasParameter(string $name): bool
    {
        return $this->wrapped->hasParameter($name);
    }

    public function setParameter(string $name, \UnitEnum|float|array|bool|int|string|null $value)
    {
        return $this->wrapped->setParameter($name, $value);
    }
}
