<?php

declare(strict_types=1);

namespace Rector\Application;

use Rector\Contract\Rector\RectorInterface;
use Rector\Skipper\Skipper\Skipper;
<<<<<<< HEAD
<<<<<<< HEAD

/**
 * @see \Rector\Tests\Application\RectorRegistryTest
 */
=======
use Rector\Skipper\ValueObject\SkipMatch;
=======
>>>>>>> 0ab0a04085 (udpate tests)

>>>>>>> 484595fafb ([trav] move skipping from enterNode() to FileProcessor to run just once per file)
final class RectorRegistry
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private array $rectors,
<<<<<<< HEAD
<<<<<<< HEAD
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
     * @return RectorInterface[]
     *
     * @api used in tests
     */
    public function forPath(string $filePath): array
    {
        $rectorsForPath = [];
        foreach ($this->rectors as $rector) {
            if ($this->skipper->shouldSkipRectorAndFile($rector, $filePath)) {
                continue;
            }

=======
        private Skipper $skipper
=======
        private readonly Skipper $skipper
>>>>>>> 0ab0a04085 (udpate tests)
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

<<<<<<< HEAD
            //            $skipMatch = $this->skipper->matchSkip($rector, $filePath);
            //            if ($skipMatch instanceof SkipMatch) {
            //                if ($rector->refactor($this->cloneNode($node)) !== null) {
            //                    $this->skipper->markSkipUsed($skipMatch);
            //                }

            //                return null;
            //            }

>>>>>>> 484595fafb ([trav] move skipping from enterNode() to FileProcessor to run just once per file)
=======
>>>>>>> 0ab0a04085 (udpate tests)
            $rectorsForPath[] = $rector;
        }

        return $rectorsForPath;
<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> 484595fafb ([trav] move skipping from enterNode() to FileProcessor to run just once per file)
=======
>>>>>>> 0ab0a04085 (udpate tests)
    }
}
