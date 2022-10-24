<?php

use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;
use Rector\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->rule(RemoveAlwaysElseRector::class );
	$rectorConfig->rule(ReturnEarlyIfVariableRector::class );
};
