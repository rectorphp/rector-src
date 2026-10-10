<?php

declare(strict_types=1);

namespace Rector\Application;

use Rector\Contract\Rector\RectorInterface;
use Rector\Skipper\Skipper\Skipper;
use Rector\Skipper\ValueObject\SkipMatch;

final class RectorRegistry
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private array $rectors,
        private Skipper $skipper
    ) {
        // @todo exclude directly those that are skipped
    }

    /**
     * @return array<RectorInterface>
     */
    public function forPath(string $filePath): array
    {
        // @todo cache?
        $rectorsForPath = [];
        foreach ($this->rectors as $rector) {
            if ($this->skipper->shouldSkipRectorAndFile($rector, $filePath)) {
                //                $this->skipper->markSkipUsed($skipMatch);
                continue;
            }

            //            $skipMatch = $this->skipper->matchSkip($rector, $filePath);
            //            if ($skipMatch instanceof SkipMatch) {
            //                if ($rector->refactor($this->cloneNode($node)) !== null) {
            //                    $this->skipper->markSkipUsed($skipMatch);
            //                }

            //                return null;
            //            }

            $rectorsForPath[] = $rector;
        }

        return $rectorsForPath;

    }
}
