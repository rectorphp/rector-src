<?php

declare(strict_types=1);

namespace App;

use Rector\Caching\Contract\CacheMetaExtensionInterface;

final class EnabledFlagCacheMetaExtension implements CacheMetaExtensionInterface
{
    public function getKey(): string
    {
        return 'enabled-flag';
    }

    public function getHash(): string
    {
        return (string) file_get_contents(__DIR__ . '/../enabled.txt');
    }
}
