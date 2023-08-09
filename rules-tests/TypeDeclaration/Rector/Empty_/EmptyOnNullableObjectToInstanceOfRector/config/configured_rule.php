<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(EmptyOnNullableObjectToInstanceOfRector::class);
};
