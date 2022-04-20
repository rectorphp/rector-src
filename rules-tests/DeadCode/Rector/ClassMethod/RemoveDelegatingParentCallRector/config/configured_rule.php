<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveDelegatingParentCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveDelegatingParentCallRector::class);
};
