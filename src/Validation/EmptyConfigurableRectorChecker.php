<?php

declare(strict_types=1);

namespace Rector\Core\Validation;

use Rector\Core\Contract\Rector\RectorInterface;

final class EmptyConfigurableRectorChecker
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function check(array $rectors): void
    {
        dump($rectors);
    }
}