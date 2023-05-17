<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\StmtsAwareInterface\IfIssetToCoalescingRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(IfIssetToCoalescingRector::class);
};
