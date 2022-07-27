<?php

declare(strict_types=1);

namespace Rector\Caching;

use Rector\Caching\Contract\ValueObject\Storage\CacheStorageInterface;

final class Cache
{
    public function __construct(
        private readonly CacheStorageInterface $cacheStorage
    ) {
    }

    /**
     * @return mixed|null
     */
    public function load(string $key, string $variableKey)
    {
        return $this->cacheStorage->load($key, $variableKey);
    }

    public function save(string $key, string $variableKey, mixed $data): void
    {
        $this->cacheStorage->save($key, $variableKey, $data);
    }

    public function clear(): void
    {
        $this->cacheStorage->clear();
    }

    public function clean(string $cacheKey): void
    {
        $this->cacheStorage->clean($cacheKey);
    }
}
