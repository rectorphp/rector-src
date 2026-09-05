<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Simple\Rector\ClassMethod\DemoObjectMethodCallRenameRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DemoObjectMethodCallRenameRector::class);
};
