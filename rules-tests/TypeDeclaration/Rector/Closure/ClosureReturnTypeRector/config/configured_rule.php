<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ClosureReturnTypeRector::class);
};
