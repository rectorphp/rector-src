<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\TypedStaticPropertyInBehatContextRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([TypedStaticPropertyInBehatContextRector::class]);
};
