<?php

declare(strict_types=1);

namespace Rector\VersionBonding\Contract;

use Rector\ValueObject\PhpVersion;

/**
 * Can be implemented by @see \Rector\Contract\Rector\RectorInterface
 *
 * Rules that do not meet this PHP version will be skipped.
 *
 * Rules that also implement @see Rector\VersionBonding\Contract\DeprecatedAtVersionInterface
 * will prioritize the deprecation version over the minimum PHP version when
 * Option::EAGERLY_RESOLVE_DEPRECATIONS is false (default).
 */
interface MinPhpVersionInterface
{
    /**
     * @return PhpVersion::*
     */
    public function provideMinPhpVersion(): int;
}
