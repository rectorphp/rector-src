<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(PropertyTypeFromStrictSetterGetterRector::class);
};
