<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddParamStringTypeFromSprintfUseRector::class);
};
