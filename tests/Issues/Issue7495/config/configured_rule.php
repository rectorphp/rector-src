<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\DeadCode\Rector\StmtsAwareInterface\RemoveJustPropertyFetchForAssignRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveUnusedPrivatePropertyRector::class);
    $rectorConfig->rule(RemoveJustPropertyFetchForAssignRector::class);
};
