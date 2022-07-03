<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveJustVariableAssignRector::class);
};
