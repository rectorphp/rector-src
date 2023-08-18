<?php

declare(strict_types=1);

namespace Rector\Core\Logging;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;

final class CurrentRectorProvider
{
    private RectorInterface|PostRectorInterface|null $currentRector = null;

    public function changeCurrentRector(RectorInterface|PostRectorInterface $rector): void
    {
        $this->currentRector = $rector;
    }

    public function getCurrentRector(): RectorInterface|PostRectorInterface|null
    {
        return $this->currentRector;
    }
}
