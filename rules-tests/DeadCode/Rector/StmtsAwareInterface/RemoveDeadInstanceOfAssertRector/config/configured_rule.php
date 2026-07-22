<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadInstanceOfAssertRector::class]);
