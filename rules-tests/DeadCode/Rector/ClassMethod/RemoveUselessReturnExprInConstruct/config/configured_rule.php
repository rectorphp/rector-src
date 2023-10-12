<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstruct;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveUselessReturnExprInConstruct::class);
};
