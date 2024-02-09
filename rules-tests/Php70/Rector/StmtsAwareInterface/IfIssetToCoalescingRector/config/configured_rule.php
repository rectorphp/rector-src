<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\StmtsAwareInterface\IfIssetToCoalescingRector;

return RectorConfig::configure()->withRules([IfIssetToCoalescingRector::class]);
