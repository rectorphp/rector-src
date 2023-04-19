<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(BinaryOpNullableToInstanceofRector::class);
};
