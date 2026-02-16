<?php

declare(strict_types=1);

namespace Rector\VersionBonding\Contract;

use Rector\ValueObject\PhpVersion;

/**
 * Can be implemented by @see \Rector\Contract\Rector\RectorInterface
 *
 * Rules that resolve deprecations can expose the PHP version where the
 * deprecation starts.
 *
 * Rules that do not meet this PHP version will be skipped when
 * Option::EAGERLY_RESOLVE_DEPRECATIONS is false (default).
 *
 * @see \Rector\VersionBonding\Contract\MinPhpVersionInterface
 */
interface DeprecatedAtVersionInterface
{
    /**
     * @return PhpVersion::*
     */
    public function provideDeprecatedAtVersion(): int;
}
