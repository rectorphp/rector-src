<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\Foreach_\ChangeNestedForeachIfsToEarlyContinueRector;
use Rector\EarlyReturn\Rector\Foreach_\ReturnAfterToEarlyOnBreakRector;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\ChangeNestedIfsToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfContinueToMultiContinueRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfReturnToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;
use Rector\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryAndToEarlyReturnRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(ChangeNestedForeachIfsToEarlyContinueRector::class);
    $services->set(ChangeAndIfToEarlyReturnRector::class);
    $services->set(ChangeIfElseValueAssignToEarlyReturnRector::class);
    $services->set(ChangeNestedIfsToEarlyReturnRector::class);
    $services->set(RemoveAlwaysElseRector::class);
    $services->set(ReturnBinaryAndToEarlyReturnRector::class);
    $services->set(ChangeOrIfReturnToEarlyReturnRector::class);
    $services->set(ChangeOrIfContinueToMultiContinueRector::class);
    $services->set(ReturnAfterToEarlyOnBreakRector::class);
    $services->set(PreparedValueToEarlyReturnRector::class);
    $services->set(ReturnBinaryOrToEarlyReturnRector::class);
};
