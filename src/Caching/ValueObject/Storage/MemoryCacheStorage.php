<?php

declare(strict_types=1);

namespace Rector\Caching\ValueObject\Storage;

use Rector\Caching\Contract\ValueObject\Storage\CacheStorageInterface;
use Rector\Caching\ValueObject\CacheItem;

/**
 * inspired by https://github.com/phpstan/phpstan-src/blob/560652088406d7461c2c4ad4897784e33f8ab312/src/Cache/MemoryCacheStorage.php
 *
 * On parallel, when no native cache engine, eg: "apcu", cache live independently in each per process run, not whole processes
 */
final class MemoryCacheStorage implements CacheStorageInterface
{
    /**
     * @var array<string, CacheItem>
     */
    private array $storage = [];

    private bool $hasNativeCacheEngine = false;

    public function __construct()
    {
        $this->hasNativeCacheEngine = extension_loaded('apcu') && ini_get('apc.enable_cli');
    }

    /**
     * @return null|mixed
     */
    public function load(string $key, string $variableKey): mixed
    {
        if (!isset($this->storage[$key]) && $this->hasNativeCacheEngine) {
            $success = false;
            $data = apcu_fetch($key, $success);

            if ($success) {
                $this->storage[$key] = new CacheItem($variableKey, $data);
            }
        }

        if (! isset($this->storage[$key])) {
            return null;
        }

        $item = $this->storage[$key];
        if (! $item->isVariableKeyValid($variableKey)) {
            return null;
        }

        return $item->getData();
    }

    public function save(string $key, string $variableKey, mixed $data): void
    {
        $this->storage[$key] = new CacheItem($variableKey, $data);

        if ($this->hasNativeCacheEngine) {
            apcu_store($key, $data);
            return;
        }
    }

    public function clean(string $key): void
    {
        if (! isset($this->storage[$key])) {
            if ($this->hasNativeCacheEngine && apcu_exists($key)) {
                apcu_delete($key);
            }

            return;
        }

        unset($this->storage[$key]);
    }

    public function clear(): void
    {
        $this->storage = [];

        if ($this->hasNativeCacheEngine) {
            apcu_clear_cache();
        }
    }
}
