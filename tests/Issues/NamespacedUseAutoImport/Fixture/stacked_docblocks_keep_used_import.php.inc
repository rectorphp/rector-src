<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Carbon\Carbon;
use Carbon\CarbonInterface;

final class StackedDocblocksKeepUsedImport
{
    /**
     * @var CarbonInterface|null
     */
    /**
     * @see Carbon
     */
    public $deletedAt;
}

?>
