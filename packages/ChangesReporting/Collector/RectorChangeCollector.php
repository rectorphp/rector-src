<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Collector;

use PhpParser\Node;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\Logging\CurrentRectorProvider;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;

final class RectorChangeCollector
{
    public function __construct(
        private readonly CurrentRectorProvider $currentRectorProvider,
        private readonly CurrentFileProvider $currentFileProvider
    ) {
    }

    /**
     * @internal Use file-> method instead
     */
    public function notifyNodeFileInfo(Node $node): void
    {
        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            // this file was changed before and this is a sub-new node
            // array Traverse to all new nodes would have to be used, but it's not worth the performance
            return;
        }

        $currentRector = $this->currentRectorProvider->getCurrentRector();
        if (! $currentRector instanceof RectorInterface) {
            return;
        }

        $rectorWithLineChange = new RectorWithLineChange($currentRector::class, $node->getLine());
        $file->addRectorClassWithLine($rectorWithLineChange);
    }
}
