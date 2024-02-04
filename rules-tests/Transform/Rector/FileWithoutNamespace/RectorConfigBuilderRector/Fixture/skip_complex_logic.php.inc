<?php

declare(strict_types=1);

use OndraM\CiDetector\CiDetector;
use Rector\Caching\ValueObject\Storage\MemoryCacheStorage;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    if ((new CiDetector())->isCiDetected()) {
        $rectorConfig->cacheClass(MemoryCacheStorage::class);
    }
};
