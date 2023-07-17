<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/return-type-declaration.php');
    $rectorConfig->import(__DIR__ . '/parameter-type-declaration.php');
    $rectorConfig->import(__DIR__ . '/property-type-declaration.php');

    $rectorConfig->rules([EmptyOnNullableObjectToInstanceOfRector::class]);
};
