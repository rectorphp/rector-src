<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Naming\Rector\VarLikeIdentifier\SnakeToCamelCasePropertiesAndVariablesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SnakeToCamelCasePropertiesAndVariablesRector::class);
};
