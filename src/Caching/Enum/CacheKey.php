<?php

declare(strict_types=1);

namespace Rector\Caching\Enum;

/**
 * @enum
 */
final class CacheKey
{
    public const string CONFIGURATION_HASH_KEY = 'configuration_hash';

    public const string FILE_HASH_KEY = 'file_hash';
}
