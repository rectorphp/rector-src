<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\AddTestsVoidReturnTypeWhereNoReturnRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddTestsVoidReturnTypeWhereNoReturnRector::class);
};
