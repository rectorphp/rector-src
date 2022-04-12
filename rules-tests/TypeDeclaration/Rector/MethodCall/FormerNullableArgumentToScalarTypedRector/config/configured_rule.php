<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\MethodCall\FormerNullableArgumentToScalarTypedRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(FormerNullableArgumentToScalarTypedRector::class);
};
