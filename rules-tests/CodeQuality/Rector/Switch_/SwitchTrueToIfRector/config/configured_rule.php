<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(\Rector\CodeQuality\Rector\Switch_\SwitchTrueToIfRector::class);
};
