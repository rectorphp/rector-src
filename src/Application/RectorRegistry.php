<?php

declare(strict_types=1);

namespace Rector\Application;

use Rector\Contract\Rector\RectorInterface;
use Rector\Skipper\Skipper\Skipper;

final class RectorRegistry
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private array $rectors,
        private readonly Skipper $skipper
    ) {
    }

    /**
     * @param RectorInterface[] $rectors
     * @api used in tests to update the active rules
     *
     * @internal Used only in Rector core, not supported outside. Might change any time.
     */
    public function refreshRectors(array $rectors): void
    {
        $this->rectors = $rectors;
    }

    /**
     * @return array<RectorInterface>
     */
    public function forPath(string $filePath): array
    {
        $rectorsForPath = [];
        foreach ($this->rectors as $rector) {
            if ($this->skipper->shouldSkipRectorAndFile($rector, $filePath)) {
                continue;
            }

            $rectorsForPath[] = $rector;
        }

        return $rectorsForPath;
    }
}
