<?php

declare(strict_types=1);

namespace Rector\Caching\Detector;

use Rector\Contract\Rector\RectorInterface;
use Rector\Caching\Cache;
use Rector\Caching\Enum\CacheKey;
use Rector\Exception\ShouldNotHappenException;
use Rector\Util\FileHasher;

final readonly class KaizenRulesDetector
{
    public function __construct(
        private Cache $cache,
        private FileHasher $fileHasher
    ) {
    }

    private function getCacheKey(): string
    {
        return CacheKey::KAIZEN_RULES . '_' . $this->fileHasher->hash(getcwd());
    }

    public function addRule(string $rectorClass): void
    {
        $cachedValue = $this->loadRules();

        $appliedRectorClasses = array_unique(array_merge($cachedValue, [$rectorClass]));
        $this->cache->save($this->getCacheKey(), CacheKey::KAIZEN_RULES, $appliedRectorClasses);
    }

    /**
     * @return array<class-string<RectorInterface>>
     */
    public function loadRules(): array
    {
        $key = $this->getCacheKey();
        $rules = $this->cache->load($key, CacheKey::KAIZEN_RULES) ?? [];

        if (! is_array($rules)) {
            throw new ShouldNotHappenException();
        }

        return array_unique($rules);
    }
}
