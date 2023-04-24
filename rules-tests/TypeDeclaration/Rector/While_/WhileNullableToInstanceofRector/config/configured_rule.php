<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(WhileNullableToInstanceofRector::class);
};
