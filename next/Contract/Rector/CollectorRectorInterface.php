<?php

declare(strict_types=1);

namespace Rector\Contract\Rector;

use PHPStan\Node\CollectedDataNode;

/**
 * @api
 */
interface CollectorRectorInterface extends RectorInterface
{
    public function setCollectedDataNode(CollectedDataNode $collectedDataNode): void;

    public function getCollectedDataNode(): CollectedDataNode;
}
