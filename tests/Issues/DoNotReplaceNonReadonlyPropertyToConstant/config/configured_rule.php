<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        ChangeReadOnlyPropertyWithDefaultValueToConstantRector::class,
        TypedPropertyFromAssignsRector::class,
    ]);
};
