<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper\Custom;

interface CustomSkipperInterface
{
    /**
     * Needed to determine changes in the configuration.
     * Leave as empty string or set to a version if you need to invalidate the cache.
     */
    public const IMPLEMENTATION_HASH = '';
}
