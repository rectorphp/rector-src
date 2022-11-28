<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReturnTypeFromStrictNativeCallRector::class);

    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');
};
