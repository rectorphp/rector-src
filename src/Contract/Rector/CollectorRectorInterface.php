<?php

declare(strict_types=1);

namespace Rector\Core\Contract\Rector;

use PHPStan\Collectors\Collector;

/**
 * @api
 */
interface CollectorRectorInterface extends RectorInterface
{
    /**
     * @return class-string<Collector>
     */
    public function getCollectorType(): string;
}
