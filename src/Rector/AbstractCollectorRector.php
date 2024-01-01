<?php

declare(strict_types=1);

namespace Rector\Core\Rector;

use PHPStan\Node\CollectedDataNode;
use Rector\Contract\Rector\CollectorRectorInterface;
use Rector\Exception\ShouldNotHappenException;
use Webmozart\Assert\Assert;

abstract class AbstractCollectorRector extends AbstractRector implements CollectorRectorInterface
{
    private ?CollectedDataNode $collectedDataNode = null;

    /**
     * @api used via optional contract
     */
    public function setCollectedDataNode(CollectedDataNode $collectedDataNode): void
    {
        Assert::isAOf(static::class, CollectorRectorInterface::class);
        $this->collectedDataNode = $collectedDataNode;
    }

    public function getCollectedDataNode(): CollectedDataNode
    {
        if (! $this->collectedDataNode instanceof CollectedDataNode) {
            throw new ShouldNotHappenException('CollectedDataNode is not set');
        }

        // this should be called only from CollectorRectorInterface
        return $this->collectedDataNode;
    }
}
